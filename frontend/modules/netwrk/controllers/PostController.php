<?php
namespace frontend\modules\netwrk\controllers;

use frontend\components\BaseController;
use frontend\components\UtilitiesFunc;
use frontend\modules\netwrk\models\Topic;
use frontend\modules\netwrk\models\Group;
use frontend\modules\netwrk\models\City;
use frontend\modules\netwrk\models\Post;
use frontend\modules\netwrk\models\User;
use frontend\modules\netwrk\models\Vote;
use frontend\modules\netwrk\models\WsMessages;
use frontend\modules\netwrk\models\UserMeet;
use yii\helpers\Url;
use yii\db\Query;
use yii\data\Pagination;
use Yii;
use yii\web\Cookie;

class PostController extends BaseController
{
    private $currentUser = 1;

    public function actionResetPostCount(){
        $posts = Post::find()->all();
        foreach ($posts as $key => $value) {
            $value->brilliant_count = null;
            $value->save();
        }
    }

    public function actionUpdateViewPost()
    {
        $id_post = $_POST['post'];
        $post = Post::findOne($id_post);
        $post->update(false);
    }
    public function actionIndex()
    {
        if (isset($_GET['group'])) {
            $topic_id = $_GET['group'];
            $topic = Topic::find()->where('id =' . $topic_id)->andWhere('status != -1')->one();
            return $this->render($this->getIsMobile() ? 'mobile/index' : '', ['topic' => $topic, 'city_id' => $topic->group->city_id]);
        } else {
            $topic_id = $_GET['topic'];
            $topic = Topic::find()->where('id =' . $topic_id)->andWhere('status != -1')->one();
            return $this->render($this->getIsMobile() ? 'mobile/index' : '', ['topic' => $topic, 'city' => $topic->city->id]);
        }
    }

    public function actionCreatePost($city,$topic)
    {
        $post_id = isset($_GET['post_id']) ? $_GET['post_id'] : '';
        $data = [];
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/netwrk/user/login','url_callback'=> Url::base(true).'/netwrk/post?topic='.$topic]);
        }
        $top = Topic::findOne($topic);
        $cty = City::findOne($city);
        if($post_id) {
            $post = Post::findOne($post_id);
        }
        if ($cty->office == 'Ritchey Woods Nature Preserve') {
            $cty->zip_code = 'Netwrk hq';
        }
        $data['topic'] = $top;
        $data['city'] = $cty;
        if(isset($post)) {
            $data['post'] = $post;
        }
        return $this->render('mobile/create', $data);
    }

    public function actionNewPost()
    {
        // $city = $_POST['city'];
        $currentUser = Yii::$app->user->id;
        $topic = $_POST['topic'];
        $post = $_POST['post'];
        $post_id = isset($_POST['post_id']) ? $_POST['post_id'] : '';
        $message = $_POST['message'];
        $current_date = date('Y-m-d H:i:s');

        if($post_id) {
            $Post = POST::find()->where(['id' => $post_id])->one();
            $Post->title = $post;
            $Post->content = $message;
            $Post->topic_id = $topic;
            $Post->update();
        } else {
            $Post = new Post;
            $Post->title = $post;
            $Post->content = $message;
            $Post->topic_id = $topic;
            $Post->user_id = $currentUser;
            $Post->post_type = 1;
            $Post->save();
        }
    }

    public function actionGetAllPost(){
        $currentUser = Yii::$app->user->id;
        $maxlength = Yii::$app->params['MaxlenghtMessageDesktop'];
        $maxlengthMobile = Yii::$app->params['MaxlenghtMessageMobile'];

        $filter = $_POST['filter'] ? $_POST['filter'] : 'post';
        $topic_id = $_POST['topic'] ? $_POST['topic'] : 0;

        $pageSize = $_POST['size'] ? $_POST['size'] : 30;
        $page = $_POST['page'] ? $_POST['page'] : 1;

        switch ($filter) {
            case 'post':
            $condition = 'created_at';
            break;
            case 'brilliant':
            $condition = 'brilliant_count';
            break;
            case 'view':
            $condition = 'view_count';
            break;
        }

        $posts = Post::find()->where('topic_id ='.$topic_id. ' AND post_type = 1')->andWhere('status != -1')->with('topic','feedbackStat')->orderBy([$condition=> SORT_DESC]);
        $pages = new Pagination(['totalCount' => $posts->count(),'pageSize'=>$pageSize,'page'=> $page - 1]);
        $posts = $posts->offset($pages->offset)->limit($pages->limit)->all();

        $data = [];
        foreach ($posts as $key => $value) {
            $feedback_stat = ($value->feedbackStat) ? $value->feedbackStat->points : 0;

            if($feedback_stat > Yii::$app->params['FeedbackHideObjectLimit']) {
                $num_view = UtilitiesFunc::ChangeFormatNumber($value->view_count ? $value->view_count : 0);
                $num_comment = UtilitiesFunc::ChangeFormatNumber($value->comment_count ? $value->comment_count + 1 : 1);
                $num_brilliant = UtilitiesFunc::ChangeFormatNumber($value->brilliant_count ? $value->brilliant_count : 0);
                $num_date = UtilitiesFunc::FormatDateTime($value->created_at);

                $content = $value->content;

                if($this->getIsMobile() && strlen($content) > $maxlengthMobile){
                    $content = substr($content,0,$maxlengthMobile) ;
                    $content = $content." ...<span class='show_more'>show more</span>";
                }elseif(!$this->getIsMobile() && strlen($content) > $maxlength){
                    $content = substr($content,0,$maxlength) ;
                    $content = $content." ...<span class='show_more'>show more</span>";
                }

                $user_profile = User::findOne($value->user_id)->profile;

                $user_name = $user_profile->first_name.' '.$user_profile->last_name;
                $user_photo = $user_profile->photo;

                if ($user_photo == null){
                    $image = Url::to('@web/img/icon/no_avatar.jpg');
                }else{
                    $image = Url::to('@web/uploads/'.$value->user_id.'/'.$user_photo);
                }
                $currentVote = null;

                if($currentUser){
                    $currentVote = Vote::find()->where('user_id= '.$currentUser.' AND post_id= '.$value->id)->one();
                    $userMeet = UserMeet::find()->where('user_id_1 ='.$currentUser.' AND user_id_2='.$value->user_id)->one();
                }

                if($currentVote && $currentVote->status == 1){
                    $isVote = 1;
                }else{
                    $isVote = 0;
                }

                if($userMeet && $userMeet->status == 1){
                    $meet = 1;
                }else{
                    $meet = 0;
                }

                $ws_message = new WsMessages();

                $stream_count = $ws_message->find()->where('ws_messages.post_id ='.$value->id. ' AND post_type = 1')->joinWith([
                    'feedbackStat' => function($query) {
                        $query->andWhere('points > 0');
                    }
                ])->all();

                $like_feedback_count = $ws_message->find()->where('ws_messages.post_id ='.$value->id. ' AND post_type = 1')->joinWith([
                    'feedback' => function($query) {
                        $query->andWhere('feedback = "like"');
                    },'feedbackStat' => function($query) {
                        $query->andWhere('points > '.Yii::$app->params['FeedbackHideObjectLimit']);
                    }
                ])->all();

                $fun_feedback_count = $ws_message->find()->where('ws_messages.post_id ='.$value->id. ' AND post_type = 1')->joinWith([
                    'feedback' => function($query) {
                        $query->andWhere('feedback = "fun"');
                    },'feedbackStat' => function($query) {
                        $query->andWhere('points > '.Yii::$app->params['FeedbackHideObjectLimit']);
                    }
                ])->all();

                $angle_feedback_count = $ws_message->find()->where('ws_messages.post_id ='.$value->id. ' AND post_type = 1')->joinWith([
                    'feedback' => function($query) {
                        $query->andWhere('feedback = "angle"');
                    },'feedbackStat' => function($query) {
                        $query->andWhere('points > '.Yii::$app->params['FeedbackHideObjectLimit']);
                    }
                ])->all();

                $post = array(
                    'id' => $value->id,
                    'topic_name' => $value->topic->title,
                    'topic_id' =>$value->topic_id,
                    'title' =>$value->title,
                    'content' =>$content,
                    'num_view' => $num_view > 0 ? $num_view : 0,
                    'num_comment' => $num_comment ? $num_comment: 0,
                    'num_brilliant'=> $num_brilliant ? $num_brilliant : 0,
                    'user_name' => $user_name,
                    'avatar' => $image,
                    'created_at' => $value->created_at,
                    'update_at' =>$num_date,
                    'is_vote' => $isVote,
                    'post_user_id' => $value->user_id,
                    'user' => $currentUser,
                    'feedback_points' => $feedback_stat,
                    'stream_count' => count($stream_count),
                    'like_feedback_count' => count($like_feedback_count),
                    'fun_feedback_count' => count($fun_feedback_count),
                    'angle_feedback_count' => count($angle_feedback_count),
                    'meet' => $meet
                );

                array_push($data,$post);
            }
        }

        $temp = array ('status'=> 1 ,'data'=> $data);
        $hash = json_encode($temp);
        return $hash;
    }

    public function actionVotePost(){
        if ($this->getIsMobile() && Yii::$app->user->isGuest) {
            $post_id = $_POST['post_id'];
            $post = Post::findOne($post_id);
            return $this->redirect(['/netwrk/user/login','url_callback'=> Url::base(true).'/netwrk/post?topic='.$post->topic_id]);
        }

        $currentUser = Yii::$app->user->id;
        $post_id = $_POST['post_id'];

        $current_date = date('Y-m-d H:i:s');
        $curVote = Vote::find()->where('user_id = '.$currentUser.' AND post_id = '.$post_id)->one();

        if($curVote){
            if($curVote->status == 1){
                $curVote->status = 0;
                $curVote->save();
            }else{
                $curVote->status = 1;
                $curVote->save();
            }
        }else{
            $vote = new Vote;
            $vote->post_id = $post_id;
            $vote->user_id = $currentUser;
            $vote->status = 1;
            $vote->created_at = $current_date;
            $vote->save();
        }

        $post = Post::findOne($post_id);
        $temp = array ('status'=> 1 ,'data'=> $post->brilliant_count);
        $hash = json_encode($temp);

        return $hash;
    }

    /**
     * Get the general topics and discussion happens in users selected zipcode area.
     * @return string
     */
    public function actionGetLocalChatInbox()
    {
        $data = [];
        $cookies = Yii::$app->request->cookies;
        $zipCode = $cookies->getValue('nw_zipCode');
        $systemUserId = 1;
        $limit = 5;

        //if city is entered on cover page then zipcode is 0,
        //IF a person enters a city, the most active general chat will show (meaning the post with the most chats)
        if($zipCode == 0) {
            $cityName = $cookies->getValue('nw_city');
            $cities = City::find()
                ->where(['name' => $cityName])
                ->all();

            $city_array = [];
            foreach($cities as $city) {
                $city_array[] = $city->id;
            }
        } else {
            $cities = City::find()
                ->where('zip_code = '.$zipCode)
                ->all();

            $city_array = [];
            foreach($cities as $city) {
                $city_array[] = $city->id;
            }
        }

        //get genral topic and post from city ids (city ids of cover page zipcodes)
        $query = new Query();
        $results = $query->select('topic.id as topic_id, topic.title as topic_title, topic.city_id, city.zip_code, post.*')
            ->from('topic')
            ->join('JOIN', 'post', 'post.topic_id = topic.id')
            ->join('JOIN', 'city', 'city.id = topic.city_id')
            ->leftJoin('feedback_stat','feedback_stat.post_id = post.id')
            ->where([
                'topic.user_id' => $systemUserId,
                'topic.city_id' => $city_array,
                ]
            )
            ->andWhere(['not',['topic.status'=> '-1']])
            ->andWhere(['not',['post.status'=> '-1']])
            ->andWhere('(feedback_stat.points > '.Yii::$app->params['FeedbackHideObjectLimit'].' OR feedback_stat.points IS NULL)')
            ->orderBy('topic.post_count DESC')
            ->limit($limit)
            ->all();

        $local_topics = [];
        if ($results) {
            foreach ($results as $key => $value) {
                $user_photo = User::findOne($value['user_id'])->profile->photo;
                if ($user_photo == null){
                    $image = 'img/icon/no_avatar.jpg';
                }else{
                    $image = 'uploads/'.$value['user_id'].'/'.$user_photo;
                }

                $num_comment = UtilitiesFunc::ChangeFormatNumber($value['comment_count'] ? $value['comment_count'] + 1 : 1);
                $num_brilliant = UtilitiesFunc::ChangeFormatNumber($value['brilliant_count'] ? $value['brilliant_count'] : 0);
                $num_date = UtilitiesFunc::FormatTimeChat($value['created_at']);
                $item = [
                    'id'=> $value['id'],
                    'post_title'=> $value['title'],
                    'post_content'=> $value['content'],
                    'topic_id'=> $value['topic_id'],
                    'topic_name'=> $value['topic_title'],
                    'city_id' =>  $value['city_id'],
                    'city_name' =>  $value['zip_code'],
                    'title'=> $value['title'],
                    'content'=> $value['content'],
                    'num_comment' => $num_comment ? $num_comment: 0,
                    'num_brilliant'=> $num_brilliant ? $num_brilliant : 0,
                    'avatar'=> $image,
                    'update_at'=> $num_date,
                    'real_update_at' => $value['chat_updated_time'] ? $value['chat_updated_time'] : $value['created_at']
                ];
                array_push($local_topics, $item);
            }
        }

        $data = $local_topics;
        return json_encode($data);
    }
    /**
     * Get the local party lines in users selected zip code area.
     * @return string
     */
    public function actionGetLocalPartyLines()
    {
        $data = [];
        $cookies = Yii::$app->request->cookies;
        $zipCode = $cookies->getValue('nw_zipCode');

        $limit = Yii::$app->params['LimitObjectFeedGlobal'];

        //if city is entered on cover page then zipcode is 0,
        //IF a person enters a city, the most active general chat will show (meaning the post with the most chats)
        if($zipCode == 0) {
            $cityName = $cookies->getValue('nw_city');
            $cities = City::find()
                ->where(['name' => $cityName])
                ->all();

            $city_array = [];
            foreach($cities as $city) {
                $city_array[] = $city->id;
            }
        } else {
            $cities = City::find()
                ->where('zip_code = '.$zipCode)
                ->all();

            $city_array = [];
            foreach($cities as $city) {
                $city_array[] = $city->id;
            }
        }

        $city_ids = implode(',',$city_array);

        // Active party lines near cover zip code
        $party_lines_posts = Post::GetBrilliantPostsByCities($limit, $city_ids);

        $local_topics = [];
        if ($party_lines_posts) {
            foreach ($party_lines_posts as $key => $value) {
                $user_photo = $value['photo'];
                if ($user_photo == null){
                    $image = 'img/icon/no_avatar.jpg';
                }else{
                    $image = 'uploads/'.$value['user_id'].'/'.$user_photo;
                }

                $num_comment = UtilitiesFunc::ChangeFormatNumber($value['comment_count'] ? $value['comment_count'] + 1 : 1);
                $num_brilliant = UtilitiesFunc::ChangeFormatNumber($value['brilliant_count'] ? $value['brilliant_count'] : 0);
                $num_date = UtilitiesFunc::FormatTimeChat($value['created_at']);
                $item = [
                    'id'=> $value['id'],
                    'post_title'=> $value['title'],
                    'post_content'=> $value['content'],
                    'topic_id'=> $value['topic_id'],
                    'topic_name'=> $value['topic_title'],
                    'city_id' =>  $value['city_id'],
                    'city_name' =>  $value['zip_code'],
                    'title'=> $value['title'],
                    'content'=> $value['content'],
                    'num_comment' => $num_comment ? $num_comment: 0,
                    'num_brilliant'=> $num_brilliant ? $num_brilliant : 0,
                    'avatar'=> $image,
                    'update_at'=> $num_date,
                    'real_update_at' => $value['chat_updated_time'] ? $value['chat_updated_time'] : $value['created_at']
                ];
                array_push($local_topics, $item);
            }
        }

        return json_encode($local_topics);
    }
    public function actionGetChatInbox()
    {
        $return = '';
        $data = [];
        $currentUser = Yii::$app->user->id;

        $data = json_decode($this->actionGetLocalChatInbox(), true);

        $local_party_lines = json_decode($this->actionGetLocalPartyLines(), true);

        if($currentUser) {
            $query = new Query();

            //fetch currents users joined post details(post with topic, city details) with chat discusstion count
            //user join post means have a entry in ws_messages table for that post of current user.
            $messages = $query->select('p.*, c.notification_count as notification_count, p.id as post_id, t.title as topic_title, t.city_id as city_id, city.name as city_name')
                ->from('ws_messages w')
                ->innerJoin('post p', '`p`.`id` = `w`.`post_id`')
                ->innerJoin('topic t', '`t`.`id` = `p`.`topic_id`')
                ->innerJoin('city', '`city`.`id` = `t`.`city_id`')
                ->leftJoin('chat_discussion c', '(c.user_id = :currentUser AND c.post_id = w.post_id )')
                ->leftJoin('feedback_stat pfs','pfs.post_id = p.id')
                ->addParams([':currentUser' => $currentUser])
                ->where('w.user_id = ' . $currentUser . ' AND w.post_type = 1')
                ->andWhere('(pfs.points > '.Yii::$app->params['FeedbackHideObjectLimit'].' OR pfs.points IS NULL)')
                ->distinct()
                ->all();
            //print $messages->createCommand()->getRawSql();
            //die();
            if ($messages) {

                foreach ($messages as $key => $message) {
                    if ($message['status'] != -1) {
                        $user_photo = User::findOne($message['user_id'])->profile->photo;
                        if ($user_photo == null) {
                            $image = 'img/icon/no_avatar.jpg';
                        } else {
                            $image = 'uploads/' . $message['user_id'] . '/' . $user_photo;
                        }

                        $currentVote = Vote::find()->where('user_id= ' . $currentUser . ' AND post_id= ' . $message['post_id'])->one();
                        $num_comment = UtilitiesFunc::ChangeFormatNumber($message['comment_count'] ? $message['comment_count'] + 1 : 1);
                        $num_brilliant = UtilitiesFunc::ChangeFormatNumber($message['brilliant_count'] ? $message['brilliant_count'] : 0);
                        $num_date = UtilitiesFunc::FormatTimeChat($message['created_at']);
                        $item = [
                            'id' => $message['post_id'],
                            'post_title' => $message['title'],
                            'post_content' => $message['content'],
                            'topic_id' => $message['topic_id'],
                            'topic_name' => $message['topic_title'],
                            'city_id' => $message['city_id'],
                            'city_name' => $message['city_name'],
                            'title' => $message['title'],
                            'content' => $message['content'],
                            'num_comment' => $num_comment ? $num_comment : 0,
                            'num_brilliant' => $num_brilliant ? $num_brilliant : 0,
                            'avatar' => $image,
                            'update_at' => $num_date,
                            'real_update_at' => $message['chat_updated_time'] ? $message['chat_updated_time'] : $message['created_at'],
                            'discussion_notification_count' => $message['notification_count']
                        ];
                        array_push($data, $item);
                    }
                }

                // return strtotime($data[0]['real_update_at']) - strtotime($data[1]['real_update_at']);die;
                usort($data, function ($a, $b) {
                    return strtotime($b['real_update_at']) - strtotime($a['real_update_at']);
                });
            }
        }

        $return = [
            'linesData' => $data,
            'localPartyLines' => $local_party_lines
        ];

        //$data = !empty($data) ? json_encode($data) : false;
        return json_encode($return);
    }

    /**
     * [Function is used to create private post for 2 user want to chat private]
     * @param   $user_id   []
     * @return             [true/false]
     */
    public function actionSetPrivatePost()
    {
        $currentUser = Yii::$app->user->id;
        $post_private = new POST();
        $post_private->title = 'private'.time();
        $post_private->content = 'content private'.time();
        $post_private->user_id = $currentUser;
        $post_private->post_type = 0;
        if($post_private->save(false)) {
            return $post_private->id;
        } else {
            return false;
        }
    }

    /**
     * [Function is used to get info of post when user click on one post of list post in meet page]
     * @param   $post_id   [post]
     * @param   $user_id
     * @return             [data]
     */

    public function actionGetInfoPost()
    {
        $post_id = isset($_GET['post_id']) ? $_GET['post_id'] : false;
        if ($post_id) {
            $post = POST::find()->where('id = '. $post_id . ' AND post_type = 1')->one();
            if ($post) {
                $data = [
                    'id' => $post->id,
                    'title' => $post->title,
                    'content' => $post->content,
                    'topic_id' => $post->topic_id,
                    'user_id' => $post->user_id,
                    'created_at' => $post->created_at,
                    'updated_at' => $post->updated_at,
                    'view_count' => $post->view_count,
                    'brilliant_count' => $post->brilliant_count,
                    'comment_count' => $post->comment_count,
                    'post_type' => $post->post_type
                ];
                $data = json_encode($data);
                return $data;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function actionGetPostById()
    {
        $post_id = isset($_GET['post_id']) ? $_GET['post_id'] : false;

        if ($post_id) {
            $post = POST::find()->where(['id' => $post_id])->with('topic')->one();
            if ($post) {
                $data = [
                    'id' => $post->id,
                    'title' => $post->title,
                    'content' => $post->content,
                    'topic_id' => $post->topic_id,
                    'topic_name' => $post->topic->title,
                    'city_id' => $post->topic->city_id,
                    'city_name' => $post->topic->city->name,
                    'city_zipcode' => $post->topic->city->zip_code,
                    'user_id' => $post->user_id,
                    'created_at' => $post->created_at,
                    'updated_at' => $post->updated_at,
                    'view_count' => $post->view_count,
                    'brilliant_count' => $post->brilliant_count,
                    'comment_count' => $post->comment_count,
                    'post_type' => $post->post_type
                ];
                $data = json_encode($data);
                return $data;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * [Function is to get posts by user id]
     *
     * @return array
     */

    public function actionGetPostsByUser()
    {
        $filter = isset($_GET['filter']) ? $_GET['filter'] : '';
        $pageSize = isset($_GET['size']) ? $_GET['size'] : '';
        $page = isset($_GET['page']) ? $_GET['page'] : '';
        $currentUserId = isset($_GET['user']) ? $_GET['user'] : Yii::$app->user->id;
        $where['user_id'] = $currentUserId;

        switch ($filter) {
            case 'recent':
                $posts = Post::find()->where($where)->andWhere('status != -1')->orderBy(['created_at'=> SORT_DESC]);
                break;
            default:
                $posts = Post::find()->where($where)->andWhere('status != -1')->orderBy(['created_at'=> SORT_DESC]);
                break;
        }

        $countQuery = clone $posts;
        $totalCount = $countQuery->count();
        $pages = new Pagination(['totalCount' => $countQuery->count(),'pageSize'=>$pageSize,'page'=> $page - 1]);
        $posts = $posts->offset($pages->offset)
            ->limit($pages->limit)
            ->all();

        $data = [];
        foreach ($posts as $key => $post) {
            $num_date = UtilitiesFunc::FormatDateTime($post->created_at);
            $postArray = [
                'id' => $post->id,
                'title' => $post->title,
                'content' => $post->content,
                'topic_id' => $post->topic_id,
                'city_id' => $post->topic->city_id,
                'user_id' => $post->user_id,
                'created_at' => $post->created_at,
                'formatted_created_date' => date('M d', strtotime($post->created_at)),
                'formatted_created_date_month_year' => date('F Y', strtotime($post->created_at)),
                'updated_at' => $post->updated_at,
                'view_count' => $post->view_count,
                'brilliant_count' => $post->brilliant_count,
                'comment_count' => $post->comment_count,
                'post_type' => $post->post_type
            ];
            array_push($data, $postArray);
        }

        //Grouped activity in month
        $postsArray = array();
        foreach ($data as $item) {
            $postsArray[$item['formatted_created_date_month_year']][] = $item;
        }

        $temp = array('data' => $postsArray, 'total_count' => $totalCount);

        $hash = json_encode($temp);
        return $hash;
    }

    /**
     * @throws \yii\db\Exception
     */
    public function actionDelete(){
        $transaction = Yii::$app->db->beginTransaction();

        try {
            $currentUserId = Yii::$app->user->id;
            $currentUser = User::find()->where(array("id" => $currentUserId))->one();

            if (empty($currentUser)) {
                throw new Exception("Unknown error, please try to re-login");
            }

            if (empty($_POST['id'])) throw new Exception("Nothing to delete");

            $post = Post::findOne($_POST['id']);

            if (empty($post) || $post->user_id != $currentUserId) {
                throw new Exception("Unknown post or user");
            }

            $post->status = -1;
            $post->save();

            $transaction->commit();

            die(json_encode(array("error" => false)));
        } catch (Exception $e) {
            $transaction->rollBack();
            die(json_encode(array("error" => true, "message" => $e->getMessage())));
        }
    }

    public function actionGetStream(){
        $stream_type = $_POST['stream_type'];
        $post_id = $_POST['post_id'];

        $pageSize = 20; //$_POST['size'];
        $page = 1; //$_POST['page'];

        $ws_message = new WsMessages();

        switch ($stream_type) {
            case 'line':
                $streams = $ws_message->find()->where('ws_messages.post_id ='.$post_id. ' AND ws_messages.post_type = 1')->with('feedbackStat')
                ->joinWith([
                    'feedbackStat' => function($query) {
                        $query->andWhere('points > 0');
                    }
                ])->orderBy(['created_at' => SORT_DESC]);
                break;
            case 'fun':
                $streams = $ws_message->find()->where('ws_messages.post_id ='.$post_id. ' AND ws_messages.post_type = 1')->joinWith([
                    'feedback' => function($query) {
                        $query->andWhere('feedback = "fun"');
                    },'feedbackStat' => function($query) {
                        $query->andWhere('points > '.Yii::$app->params['FeedbackHideObjectLimit']);
                    }
                ])->orderBy(['created_at' => SORT_ASC]);
                break;
            case 'like':
                $streams = $ws_message->find()->where('ws_messages.post_id ='.$post_id. ' AND ws_messages.post_type = 1')->joinWith([
                    'feedback' => function($query) {
                        $query->andWhere('feedback = "like"');
                    },'feedbackStat' => function($query) {
                        $query->andWhere('points > '.Yii::$app->params['FeedbackHideObjectLimit'])->orderBy(['points' => SORT_DESC]);
                    }
                ]);
                break;
            case 'angle':
                $streams = $ws_message->find()->where('ws_messages.post_id ='.$post_id. ' AND ws_messages.post_type = 1')->joinWith([
                    'feedback' => function($query) {
                        $query->andWhere('feedback = "angle"');
                    },'feedbackStat' => function($query) {
                        $query->andWhere('points > '.Yii::$app->params['FeedbackHideObjectLimit'])->orderBy(['points' => SORT_DESC]);
                    }
                ]);
                break;
        }

        $streams = $streams->offset(0)->limit($pageSize)->all();

        $data = [];
        foreach ($streams as $key => $value) {
            $feedback_stat = ($value->feedbackStat) ? $value->feedbackStat->points : 0;
            if($value->first_msg == 0) {
                if($value->user->id == $this->current_user) {
                    $pchat = ChatPrivate::find()->where(['user_id'=>$value->user->id, 'post_id'=>$this->post_id])->one();
                    $profile = Profile::find()->where(['user_id'=>$pchat->user_id_guest])->one();
                    $id = $pchat->user_id_guest;
                } else {
                    $profile = Profile::find()->where(['user_id'=>$value->user->id])->one();
                    $id = $value->user->id;
                }

                $current_date = date('Y-m-d H:i:s');
                $time1 = date_create($profile->dob);
                $time2 = date_create($current_date);
                $year_old = $time1->diff($time2)->y;

                $name = $profile->first_name ." ".$profile->last_name;
                $photo = $profile->photo;
                $smg = nl2br($profile->first_name . " " . $profile->last_name . ", " . $year_old . "\n" . $value->msg);
            } else {
                $id = $value->user->id;
                $name = $value->user->profile->first_name ." ".$value->user->profile->last_name;
                $photo = $value->user->profile->photo;
                $smg = nl2br($value->msg);
            }

            if ($photo == null) {
                $image = '/img/icon/no_avatar.jpg';
            } else {
                $image = '/uploads/'.$id.'/'.$photo;
            }

            $time = UtilitiesFunc::FormatTimeChat($value->created_at);

            $item = array(
                'id' => $value->id,
                'user_id' => $id,
                'user_name' => $name,
                'avatar' => $image,
                'msg' => $smg,
                'created_at' => $time,
                'post_id' => $value->post_id,
                'post_type' => $value->post_type,
                'feedback_points' => $feedback_stat
            );

            array_push($data,$item);
        }

        $temp = array ('status'=> 1 ,'data'=> $data);
        $hash = json_encode($temp);
        return $hash;
    }
}
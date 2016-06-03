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
            return $this->render($this->getIsMobile() ? 'mobile/index' : '', ['topic' => $topic]);
        } else {
            $topic_id = $_GET['topic'];
            $topic = Topic::find()->where('id =' . $topic_id)->andWhere('status != -1')->one();
            return $this->render($this->getIsMobile() ? 'mobile/index' : '', ['topic' => $topic, 'city' => $topic->city->id]);
        }
    }

    public function actionCreatePost($city,$topic)
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/netwrk/user/login','url_callback'=> Url::base(true).'/netwrk/post?topic='.$topic]);
        }
        $top = Topic::findOne($topic);
        $cty = City::findOne($city);
        if ($cty->office == 'Ritchey Woods Nature Preserve') {
            $cty->zip_code = 'Netwrk hq';
        }
        return $this->render('mobile/create',['topic' =>$top,'city' =>$cty]);
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

        $filter = $_POST['filter'];
        $topic_id = $_POST['topic'];

        $pageSize = $_POST['size'];
        $page = $_POST['page'];

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

        $posts = Post::find()->where('topic_id ='.$topic_id. ' AND post_type = 1')->andWhere('status != -1')->with('topic')->orderBy([$condition=> SORT_DESC]);
        $pages = new Pagination(['totalCount' => $posts->count(),'pageSize'=>$pageSize,'page'=> $page - 1]);
        $posts = $posts->offset($pages->offset)->limit($pages->limit)->all();

        $data = [];
        foreach ($posts as $key => $value) {

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

            $user_photo = User::findOne($value->user_id)->profile->photo;

            if ($user_photo == null){
                $image = Url::to('@web/img/icon/no_avatar.jpg');
            }else{
                $image = Url::to('@web/uploads/'.$value->user_id.'/'.$user_photo);
            }
            $currentVote = null;
            if($currentUser){
                $currentVote = Vote::find()->where('user_id= '.$currentUser.' AND post_id= '.$value->id)->one();
            }

            if($currentVote && $currentVote->status == 1){
                $isVote = 1;
            }else{
                $isVote = 0;
            }

            $post = array(
                'id'=> $value->id,
                'topic_name'=> $value->topic->title,
                'topic_id'=>$value->topic_id,
                'title'=>$value->title,
                'content'=>$content,
                'num_view' => $num_view > 0 ? $num_view : 0,
                'num_comment' => $num_comment ? $num_comment: 0,
                'num_brilliant'=> $num_brilliant ? $num_brilliant : 0,
                'avatar'=> $image,
                'update_at'=>$num_date,
                'is_vote'=> $isVote,
                'post_user_id' => $value->user_id,
                'user' => $currentUser,
                );

            array_push($data,$post);
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
            ->where([
                'topic.user_id' => $systemUserId,
                'topic.city_id' => $city_array,
                ]
            )
            ->andWhere(['not',['topic.status'=> '-1']])
            ->andWhere(['not',['post.status'=> '-1']])
            ->orderBy('topic.post_count DESC')
            ->limit($limit)
            ->all();

        $local_topics = [];
        if ($results) {
            foreach ($results as $key => $value) {
                $user_photo = User::findOne($value->user_id)->profile->photo;
                if ($user_photo == null){
                    $image = 'img/icon/no_avatar.jpg';
                }else{
                    $image = 'uploads/'.$value->user_id.'/'.$user_photo;
                }

                $num_comment = UtilitiesFunc::ChangeFormatNumber($value->comment_count ? $value->comment_count + 1 : 1);
                $num_brilliant = UtilitiesFunc::ChangeFormatNumber($value->brilliant_count ? $value->brilliant_count : 0);
                $num_date = UtilitiesFunc::FormatTimeChat($value->created_at);
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
    public function actionGetChatInbox()
    {
        $return = '';
        $data = [];
        $currentUser = Yii::$app->user->id;

        $data = json_decode($this->actionGetLocalChatInbox(), true);

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
                ->addParams([':currentUser' => $currentUser])
                ->where('w.user_id = ' . $currentUser . ' AND w.post_type = 1')
                ->distinct()
                ->all();
            //print $messages->createCommand()->getRawSql();
            //die();
            if ($messages) {

                foreach ($messages as $key => $message) {
                    if($message->post->status != -1){
                        $user_photo = User::findOne($message->post->user_id)->profile->photo;
                        if ($user_photo == null) {
                            $image = 'img/icon/no_avatar.jpg';
                        } else {
                            $image = 'uploads/' . $message->post->user_id . '/' . $user_photo;
                        }

                        $currentVote = Vote::find()->where('user_id= ' . $currentUser . ' AND post_id= ' . $message->post->id)->one();
                        $num_comment = UtilitiesFunc::ChangeFormatNumber($message->post->comment_count ? $message->post->comment_count + 1 : 1);
                        $num_brilliant = UtilitiesFunc::ChangeFormatNumber($message->post->brilliant_count ? $message->post->brilliant_count : 0);
                        $num_date = UtilitiesFunc::FormatTimeChat($message->post->created_at);
                        $item = [
                            'id' => $message->post->id,
                            'post_title' => $message->post->title,
                            'post_content' => $message->post->content,
                            'topic_id' => $message->post->topic_id,
                            'topic_name' => $message->post->topic->title,
                            'city_id' => $message->post->topic->city_id,
                            'city_name' => $message->post->topic->city->name,
                            'title' => $message->post->title,
                            'content' => $message->post->content,
                            'num_comment' => $num_comment ? $num_comment : 0,
                            'num_brilliant' => $num_brilliant ? $num_brilliant : 0,
                            'avatar' => $image,
                            'update_at' => $num_date,
                            'real_update_at' => $message->post->chat_updated_time ? $message->post->chat_updated_time : $message->post->created_at
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

        $data = !empty($data) ? json_encode($data) : false;
        return $data;
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
}
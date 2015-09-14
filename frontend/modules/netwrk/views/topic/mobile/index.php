<?php use yii\helpers\Url; ?>
<div id="show-topic">
    <div class="header">
        <div class="back_page"><i class="fa fa-arrow-left"></i></div>
        <div class="title_page">
            <img src="<?= Url::to('@web/img/icon/netwrk_btn.png'); ?>">
            <span class="title"><?php echo $title ?></span>
        </div>
        <div class="create_topic">
            <span>Create a topic +</span>
        </div>
    </div>
    <div class="sidebar">
       <span class="title">Topics</span>
       <table class="filter_sidebar">
            <tr>
                <td class="active">Most posts</td>
                <td>Most viewed</td>
                <td>My Topics</td>
            </tr>
       </table> 
    </div>
    <div class="containt">
        
    </div>
</div>
var ProfileEdit = {
    data:{},
    params:{
        email: '',
        gender: '',
        zip: 0,
        dob: 0,
        marital_status: 'Single',
        work: '',
        education: '',
        hobbies: '',
        bio: '',
        lat:'',
        lng:''
    },
    num_len:true,
    zipcode: true,
    status_change:{
        age:true,
        zipcode: true,
        work: false,
        about:false,
        total:false
    },
    state: 'Indiana',
    modal:$('#modal_profile_edit'),
    initialize: function(){
        ProfileEdit.onClickBack();
        ProfileEdit.ShowModalProfileEdit();

        /*ProfileInfo.resetProfileInfo();
        ProfileInfo.onClickEditProfile();
        ProfileInfo.getProfileBasicInfo();*/

    },

    onClickBack: function(){
        var parent = ProfileEdit.modal.find('.back-page span');

        parent.unbind();
        parent.click(function(){
            if(isMobile){
            } else {
                $('.modal').modal('hide');
                ProfileInfo.initialize();
            }
        });
    },

    ShowModalProfileEdit: function(){
        var self = this;

        ProfileEdit.modal.modal({
            backdrop: true,
            keyboard: false
        });

        Common.CustomScrollBar(ProfileEdit.modal.find('.modal-body'));

        ProfileEdit.modal.on('hidden.bs.modal',function() {
            ProfileEdit.modal.modal('hide');
        });
        $('.modal-backdrop.in').click(function(e) {
            ProfileEdit.modal.modal('hide');
        });
    },




    resetProfileInfo: function(){
        ProfileInfo.profileBasicInfo.html('');
        ProfileInfo.profileBio.html('');
    },


    onClickEditProfile: function(){
        var parent = ProfileInfo.modal.find('.edit-profile');

        parent.unbind();
        parent.click(function(){
            if(isMobile){
            } else {
                $('.modal').modal('hide');
                ProfileEdit.initialize();
            }
        });
    },

    getProfileBasicInfo: function(){
        var self = this,
            profile_basic_info = $('#profile_basic_info'),
            profile_bio = $('#profile_bio');

        Ajax.getProfileBasicInfo().then(function(data){
            var json = $.parseJSON(data);
            ProfileInfo.data = json;

            if(ProfileInfo.data.status == 1){
                ProfileInfo.params.email = json.email;
                ProfileInfo.params.gender = json.gender;
                ProfileInfo.params.zip = json.zip;
                ProfileInfo.params.dob = json.dob;
                ProfileInfo.params.marital_status = json.marital_status;
                ProfileInfo.params.work = json.work;
                ProfileInfo.params.education = json.education;
                ProfileInfo.params.hobbies = json.hobbies;
                ProfileInfo.params.bio = json.bio;

                ProfileInfo.getTemplateProfileBasicInfo(ProfileInfo.profileBasicInfo,profile_basic_info);
                ProfileInfo.getTemplateProfileBasicInfo(ProfileInfo.profileBio,profile_bio);
            }
        });
    },

    getTemplateProfileBasicInfo: function(parent,target,callback){
        var template = _.template(target.html());
        var append_html = template({data: ProfileInfo.data});
        parent.append(append_html);

        if(_.isFunction(callback)){
            callback();
        }
    },


};
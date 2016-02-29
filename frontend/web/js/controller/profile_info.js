var ProfileInfo = {
    data:{},
    params:{
        age: 0,
        work: '',
        about: '',
        zipcode:0,
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
    modal:$('#modal_profile_info'),
    initialize: function(){
        ProfileInfo.onClickBack();
        ProfileInfo.ShowModalProfileInfo();
    },

    onClickBack: function(){
        var parent = ProfileInfo.modal.find('.back-page span');

        parent.unbind();
        parent.click(function(){
            if(isMobile){
            } else {
                $('.modal').modal('hide');
                User_Profile.initialize();
            }
        });
    },

    ShowModalProfileInfo: function(){
        var self = this;

        ProfileInfo.modal.modal({
            backdrop: true,
            keyboard: false
        });

        Common.CustomScrollBar(ProfileInfo.modal.find('.modal-body'));

        ProfileInfo.modal.on('hidden.bs.modal',function() {
            ProfileInfo.modal.modal('hide');
        });
        $('.modal-backdrop.in').click(function(e) {
            ProfileInfo.modal.modal('hide');
        });
    }
};
var Group = {
    data:{
        filter: 'recent',
        city: '',
        size: 30,
        city_name:'',
        zipcode:''

    },
    list:{
        post:{
            paging:1,
            status_paging: 1,
            loaded: 0
        },
        view:{
            paging:1,
            status_paging: 1,
            loaded: 0
        },
        recent:{
            paging:1,
            status_paging: 1,
            loaded: 0
        }
    },
    params:{
        zipcode:'',
        name:'',
        lat: '',
        lng:''
    },
    modal: '#modal_topic',
    modal_create: '#create_topic',
    tab_current: 'topic',
    init: function(){
        /*Group._onclickBack();
        Group.GetDataOnTab();
        Group.load_topic();
        Group.get_data_new_netwrk();
        Group.RedirectPostList();
        Group.scroll_bot();
        Group.OnClickSortBtn();
        Group.OnClickSelectFilter();
        Group.OnClickChangeTab();
        Group.eventClickMeetMobile();
        Group.OnClickCreateGroup();*/
        Group.OnShowModalGroup();
    },

    initialize: function(city,params){
        if (isMobile) {
            //Group.show_page_topic(city,params);
        } else {
            /*Group.OnShowModalPost();
            Group.close_modal();
            Group._onclickBack();
            Group.OnClickBackdrop();
            Group.onNetwrkLogo();
            Group.OnClickCreateGroup();*/
            //Group.show_modal_group(city,params);
            Group.OnShowModalGroup();
        }
    },

    show_modal_group: function(city,new_params){
        var self = this;
        var parent = $('#item_list_'+self.data.filter);
        var sidebar = $('.map_content .sidebar');

        if(new_params){
            self.data.zipcode = new_params.zipcode;
        }
        if(city){
            self.data.city = city;
        }

        var params = {'city': self.data.city,'zipcode': self.data.zipcode, 'filter': self.data.filter,'size': self.data.size,'page':1};

        parent.show();
        set_heigth_modal($('#modal_group'),0);
        $('#modal_group').modal({
            backdrop: true,
            keyboard: false
        });
    },

    OnShowModalGroup: function() {
        $('#create_group_modal').unbind('shown.bs.modal').on('shown.bs.modal',function(e) {
            Group.LoadGroupModal();
            //Topic.OnClickChangeTab();
        });
    },

    getTemplate: function(parent,data){
        var self = this;
        var json = $.parseJSON(data);
        var list_template = _.template($("#group_list").html());
        var append_html = list_template({groups: json.data});

        parent.append(append_html);
        self.onTemplate(json);
    },

    getTemplateModal: function(parent,data){
        return;
        var self = this;
        var json = $.parseJSON(data);
        var list_template = _.template($( "#city_name" ).html());
        var append_html = list_template({city: json.city});
        Topic.data.city_name = json.city;
        parent.append(append_html);
    },

    onTemplate: function(json){
        var self = this;

        if(json.data.length == 0){
            $('#item_group_list_'+self.data.filter).find('.no-data').show();
            self.list[self.data.filter].status_paging = 0;
        }else if(json.data.length < self.data.size && json.data.length > 0){
            $('#item_group_list_'+self.data.filter).find('.no-data').hide();
            self.list[self.data.filter].status_paging = 0;
        }else{
            $('#item_group_list_'+self.data.filter).find('.no-data').hide();
            self.list[self.data.filter].status_paging = 1;
        }
        //Topic.create_topic();
        //Topic.RedirectPostList();
        // this.create_post();
    },

    LoadGroupModal: function(){
        var self = this;
        var parent = $('#item_group_list_'+self.data.filter);
        var cityname = $('#modal_topic').find('.title_page');
        var params = {'city': Topic.data.city,'zipcode': Topic.data.zipcode, 'filter': Topic.data.filter,'size': Topic.data.size,'page':1};

        parent.show();
        // sidebar.show();
        Ajax.show_groups(params).then(function(data){
            parent.scrollTop(0);
            self.list[self.data.filter].loaded = self.list[self.data.filter].paging ;
            self.getTemplate(parent,data);
            self.getTemplateModal(cityname,data);
            Topic.CustomScrollBar();
            //Topic.filter_topic(parent);
            Topic.GetDataOnTab();
        });
    }

};

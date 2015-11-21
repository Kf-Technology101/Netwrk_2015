var Meet_setting={
    setting:{
        age: '',
        gender: '',
        distance: '',
    },
    params:{
        age: 'All',
        gender: 'All',
        distance: 'All',
    },
    status_change: {
        age: false,
        gender: false,
        distance: false,
        total: false
    },

    initialize: function(){
        Meet_setting.reset_page();
        Meet_setting._init();
        Meet_setting.get_setting();
    },

    _onControl: function(){
        Meet_setting.check_status_change();
        Meet_setting.onClickSave();
        Meet_setting.onClickReset();
    },
    reset_page: function(){
        Meet_setting.status_change.age = false;
        Meet_setting.status_change.gender = false;
        Meet_setting.status_change.distance = false;
        Meet_setting.status_change.total = false;
        Meet_setting.set_default_btn();
    },
    _init: function(){
        var span,text ;
        $('.name_user').find('p.default').hide();
        // $('.modal-footer').hide();

        if(isMobile){
            $('#show_meet .page').hide();
            $('.name_user').find('img').hide();
            $('.log_out').hide();
            var title = $('.name_user').find('span');
            span = '<p class="name">Meet Settings</p>';
            text = 'Meet Settings';
        }else{
            $('#modal_meet .page').hide();
             var title = $('.name_user').find('p.name');
             span = '<p class="name">Meet Me</p>';
             text = 'Meet Me';
        }

        if(title.size() > 0){
            title.text(text);
        }else{
            $('.name_user').append(span);
        }
        
        $('#meet_setting').show();
    },

    set_default_btn: function(){
        $('#meet_setting').find('.btn-control .cancel').addClass('disable');
        $('#meet_setting').find('.btn-control .save').addClass('disable');
    },

    check_status_change: function(){
        if(Meet_setting.status_change.age || Meet_setting.status_change.gender || Meet_setting.status_change.distance){
            Meet_setting.status_change.total = true;
        }else if(Meet_setting.status_change.age == false && Meet_setting.status_change.gender == false && Meet_setting.status_change.distance == false){
            Meet_setting.status_change.total = false;
        }
    },

    onClickSave: function(){
        var btn = $('#meet_setting').find('.btn-control .save');
        btn.unbind();
        if(Meet_setting.status_change.total){
            btn.removeClass('disable');
            btn.on('click',function(){
                Ajax.update_setting(Meet_setting.params).then(function(data){
                    var json = $.parseJSON(data);

                    Meet_setting.setting.age = json.age;
                    Meet_setting.setting.gender = json.gender;
                    Meet_setting.setting.distance = json.distance;
                    Meet_setting.params.age = json.age;
                    Meet_setting.params.gender = json.gender;
                    Meet_setting.params.distance = json.distance;
                });
                Meet_setting.set_default_btn();
            });
        }else{
            btn.addClass('disable');
        }
        
    },

    onClickReset: function(){
        var btn = $('#meet_setting').find('.btn-control .cancel');

        btn.unbind();
        if(Meet_setting.status_change.total){
            btn.removeClass('disable');
            btn.on('click',function(){
                Meet_setting.initialize();
                Meet_setting.set_default_btn();
            });
        }else{
            btn.addClass('disable');
        }
        
    },

    get_setting: function(){
        Ajax.get_setting().then(function(data){
            var json = $.parseJSON(data);

            Meet_setting.setting.age = json.age;
            Meet_setting.setting.gender = json.gender;
            Meet_setting.setting.distance = json.distance;
            Meet_setting.params.age = json.age;
            Meet_setting.params.gender = json.gender;
            Meet_setting.params.distance = json.distance;

            Meet_setting.gender_radio();
            Meet_setting.sliderArea();
            Meet_setting.sliderAge();

        });
    },

    gender_radio: function(){
        $.each($('input.input_radio'),function(i,e){
            if (Meet_setting.setting.gender == $(e).val()) {
                $(e).prop("checked", true);
            }

            $(e).unbind();
            $(e).on('click',function(){

                if (Meet_setting.setting.gender != $(e).val()) {
                    Meet_setting.status_change.gender = true;
                }else if(Meet_setting.setting.gender == $(e).val()){
                    Meet_setting.status_change.gender = false;
                }

                $(e).bind();
                Meet_setting.params.gender = $(e).val();
                
                Meet_setting._onControl();
            })
        });
    },

    sliderArea: function(){
        var distances = ["All",25,50,100,200],
            value = 0;

        $.each(distances,function(i,e){
            if(Meet_setting.setting.distance == e){
                value = i;
                if(e == "All"){
                    $(".search_area").find('.value').text(e);
                }else{
                    $(".search_area").find('.value').text(e + ' mi');
                }
            }
        });

        $("#circles-slider-area")
        .slider({
            max: distances.length-1,
            min: 0,
            value: value
        })
        .slider("pips",{
            first: "pip",
            last: "pip"
        });

        $("#circles-slider-area").on("slidechange", function(e,ui) {
            Meet_setting.params.distance = distances[ui.value];

            if(Meet_setting.setting.distance != distances[ui.value]){
                Meet_setting.status_change.distance = true;
            }else{
                Meet_setting.status_change.distance = false;
            }

            if(distances[ui.value] == "All"){
                $(".search_area").find('.value').text( distances[ui.value]);
            }else{
                $(".search_area").find('.value').text( distances[ui.value] + " mi");
            }

            Meet_setting._onControl();
        });
    },

    sliderAge: function(){
        var Ages = ["All",25,35,50,75],
            value = 0;

        $.each(Ages,function(i,e){
            if(Meet_setting.setting.age == e){
                value = i;
                if(e == "All"){
                    $(".search_age").find('.value').text(e);
                }else{
                    $(".search_age").find('.value').text(e + '+ yrs');
                }
            }
        });

        $("#circles-slider-age")
        .slider({
            max: Ages.length-1,
            min: 0,
            value: value
        })
        .slider("pips",{
            first: "pip",
            last: "pip"
        });

        $("#circles-slider-age").on("slidechange", function(e,ui) {
            Meet_setting.params.age = Ages[ui.value];

            if(Meet_setting.setting.age != Ages[ui.value]){
                Meet_setting.status_change.age = true;
            }else{
                Meet_setting.status_change.age = false;
            }

            if(Ages[ui.value] == "All"){
                $(".search_age").find('.value').text( Ages[ui.value]);
            }else{
                $(".search_age").find('.value').text( Ages[ui.value] + "+ yrs");
            }
             Meet_setting._onControl();
        });
    }
};
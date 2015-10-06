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

    initialize: function(){
        Meet_setting._init();
        Meet_setting.get_setting();
        
        Meet_setting.onClickReset();
        Meet_setting.onClickSave();
    },

    _init: function(){
        $('.page').hide();
        
        if(isMobile){
            $('.name_user').find('img').hide();
            var title = $('.name_user').find('span');
        }else{
             var title = $('.name_user').find('p');
        }
        
        if(title.size() > 0){
            title.text('Meet Settings');
        }else{
            title.append('<span class="name">Meet Settings</span>');
        }
        
        $('#meet_setting').show();
    },

    onClickSave: function(){
        var btn = $('#meet_setting').find('.btn-control .save');
        btn.unbind();

        btn.on('click',function(){
            // Meet_setting.initialize();
            
            // Meet_setting.setting.age = Meet_setting.params.age;
            // Meet_setting.setting.gender = Meet_setting.params.gender;
            // Meet_setting.setting.distance = Meet_setting.params.distance;

            console.log(Meet_setting.params);
            Ajax.update_setting(Meet_setting.params).then(function(data){

            });
        });
    },

    onClickReset: function(){
        var btn = $('#meet_setting').find('.btn-control .cancel');

        btn.unbind();

        btn.on('click',function(){
            Meet_setting.initialize();
        });
    },

    get_setting: function(){
        Ajax.get_setting().then(function(data){
            var json = $.parseJSON(data);
            console.log(json);
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
            console.log(Meet_setting.setting.gender);
            if (Meet_setting.setting.gender == $(e).val()) {
                $(e).prop("checked", true);
            }

            $(e).unbind();
            $(e).on('click',function(){
                $(e).bind();
                Meet_setting.params.gender = $(e).val();
                console.log(Meet_setting.params.gender);
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
                    $(".search_area").find('.value').text(e + 'mi');
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

            if(distances[ui.value] == "All"){
                $(".search_area").find('.value').text( distances[ui.value]);
            }else{
                $(".search_area").find('.value').text( distances[ui.value] + " mi");
            }
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
            if(Ages[ui.value] == "All"){
                $(".search_age").find('.value').text( Ages[ui.value]);
            }else{
                $(".search_age").find('.value').text( Ages[ui.value] + "+ yrs");
            }
            console.log(Meet_setting.params.age);
        });
    }
};
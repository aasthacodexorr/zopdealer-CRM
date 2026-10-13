jQuery(document).ready(function($){

    $color_list = [

        {color:'#d435a2',title:''},

        {color:'#a834bf',title:''},

        {color:'#6011cf',title:''},

        {color:'#0d0e81',title:''},

        {color:'#0237f1',title:''},

        {color:'#0d8bcd',title:''},
        
        {color:'#0e76a8',title:''},

        {color:'#16aca4',title:''},

        {color:'#3c887e',title:''},

        {color:'#157145',title:''},

        {color:'#57a773',title:''},

        {color:'#b7990d',title:''},
        
        {color:'#fcbf55',title:''},

        {color:'#ff8668',title:''},

        {color:'#ff5c6a',title:''},

        {color:'#c2183f',title:''},

        {color:'#d8226b',title:''},

        {color:'#8f2d56',title:''},
        
        {color:'#561f37',title:''},
        
        {color:'#482971',title:''},

        {color:'#000000',title:''},

        {color:'#433835',title:''},

        {color:'#797979',title:''},

        {color:'#bcc1ba',title:''},
        
        {color:'#819595',title:''}
        
    ];
    
    $('.color-picker').wrap('<div class="color-picker-wrap"></div>');

     $('.color-picker-wrap').each(function(){

            var self = $(this);

            if(self.children('.color-picker').hasClass('cp-sm')){

                self.addClass('cp-sm');

            }else if(self.children('.color-picker').hasClass('cp-lg')){

                self.addClass('cp-lg');

            }

            self.append('<ul></ul><input type="color" style="display:none;" />');

            var $foundactive = false;

            for(var i = 0; i <  $color_list.length; i++){

            var $active = '';

            if(self.children(".color-picker").val()==$color_list[i].color){

                    $active = 'class="active"';

                    $foundactive = true;

                    if(self.children(".color-picker").hasClass('cp-show')){

                        self.children('small').remove();    

                        self.append('<small>Selected Color: <code>'+$color_list[i].color+'</code></small>');
                        
                    }
            }

                self.children('ul').append('<li '+ $active+' style="background-color:'+$color_list[i].color+'" title="'+ $color_list[i].title+'"></li>');
            }

            if(!$foundactive && self.children(".color-picker").val()!='' ){

                    self.children('ul').append('<li class="active" title="Custom Color '+self.children(".color-picker").val()+'" style="background-color:'+self.children(".color-picker").val()+'"></li>');
                    
                    if(self.children(".color-picker").hasClass('cp-show')){

                        self.children('small').remove();    

                        self.append('<small>Selected Color: <code>'+self.children(".color-picker").val()+'</code></small>');
                        
                    }

            }

            self.children('ul').append('<li class="add_new" title="Add New"><span>+</span></li>');

     });

  $('.color-picker-wrap ul').on('click','li', function() {

        var self = $(this);

          if (!self.hasClass('add_new')) {   

                  if (!self.hasClass('active')) {

                      self.siblings().removeClass('active');

                        var color =rgb2hex(self.css("backgroundColor"));

                        self.parents('.color-picker-wrap').children(".color-picker").val(color);

                        self.addClass('active');
                        
                        if(self.parents('.color-picker-wrap').children(".color-picker").hasClass('cp-show')){

                            self.parents('.color-picker-wrap').children('small').remove();    

                            self.parents('.color-picker-wrap').append('<small>Selected Color: <code>'+color+'</code></small>');

                        }

                  }
          }else{
              self.parents('.color-picker-wrap').children("input[type='color']").trigger('click');
          }

      });

  $(".color-picker-wrap input[type='color']").on("change",function(){

      var self = $(this);

      self.parents('.color-picker-wrap').children('ul').children('li').removeClass('active');

      self.parents('.color-picker-wrap').children('ul').children('li.add_new').remove();

      self.parents('.color-picker-wrap').children('ul').append('<li class="active" title="Custom Color '+self.val()+'" style="background-color:'+self.val()+'"></li>');

      self.parents('.color-picker-wrap').children(".color-picker").val(self.val());

      self.parents('.color-picker-wrap').children('ul').append('<li class="add_new" title="Add New"><span>+</span></li>');
                              
    if(self.parents('.color-picker-wrap').children(".color-picker").hasClass('cp-show')){

        self.parents('.color-picker-wrap').children('small').remove();    

        self.parents('.color-picker-wrap').append('<small>Selected Color: <code>'+self.val()+'</code></small>');
        
    }

  });

    var hexDigits = new Array ("0","1","2","3","4","5","6","7","8","9","a","b","c","d","e","f"); 

    function rgb2hex(rgb) {

        rgb = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
        
        return "#" + hex(rgb[1]) + hex(rgb[2]) + hex(rgb[3]);
    }

    function hex(x) {

        return isNaN(x) ? "00" : hexDigits[(x - x % 16) / 16] + hexDigits[x % 16];

    }

});    
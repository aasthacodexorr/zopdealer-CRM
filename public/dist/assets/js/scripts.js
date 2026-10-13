/*=========================================================================================
    File Name: app-todo.js
    Description: app-todo
==========================================================================================*/
// Todo App variables
var todoNewTasksidebar = $(".todo-new-task-sidebar"),
  appContentOverlay = $(".app-content-overlay"),
  sideBarLeft = $(".sidebar-left"),
  todoTaskListWrapper = $(".todo-task-list-wrapper"),
  todoItem = $(".todo-item"),
  selectAssignLable = $(".select2-assign-label"),
  selectUsersName = $(".select2-users-name"),
  avatarUserImage = $(".avatar-user-image"),
  updateTodo = $(".update-todo"),
  addTodo = $(".add-todo"),
  markCompleteBtn = $(".mark-complete-btn"),
  newTaskTitle = $(".new-task-title"),
  taskTitle = $(".task-title"),
  noResults = $(".no-results"),
  assignedAvatarContent = $(".assigned .avatar .avatar-content"),
  todoAppMenu = $(".todo-app-menu");
  sclisttable = $(".sc-list-table");

// badge colors object define here for badge color
var badgeColors = {
  "Frontend": "badge-light-primary",
  "Backend": "badge-light-success",
  "Issue": "badge-light-danger",
  "Design": "badge-light-warning",
  "Wireframe": "badge-light-info",
}
$(function () {
  "use strict";
    
    

  // Single Date Picker
  $('.pickadate').daterangepicker({
    singleDatePicker: true,
    showDropdowns: true,
    locale: {
      format: 'MM/DD'
    }
  });

  // dragable list
  dragula([document.getElementById("todo-task-list-drag")], {
    moves: function(el, container, handle) {
      return handle.classList.contains("handle");
    }
  });


  // select assigner
  selectUsersName.select2({
    placeholder: "Unassigned",
    dropdownAutoWidth: true,
    width: '100%'
  });

  // select label
  selectAssignLable.select2({
    dropdownAutoWidth: true,
    width: '100%'
  });

  // Sidebar scrollbar
  if ($('.todo-application .sidebar-menu-list').length > 0) {
    var sidebarMenuList = new PerfectScrollbar('.sidebar-menu-list', {
      theme: "dark",
      wheelPropagation: false
    });
  }

  //  New task scrollbar
//  if (todoNewTasksidebar.length > 0) {
//    var todo_new_task_sidebar = new PerfectScrollbar('.todo-new-task-sidebar', {
//      theme: "dark",
//      wheelPropagation: false
//    });
//  }

  // Task list scrollbar
  if ($('.todo-application .todo-task-list').length > 0) {
    var sidebar_todo = new PerfectScrollbar('.todo-task-list', {
      theme: "dark",
      wheelPropagation: false
    });
  }

  // New compose message compose field
  var composeEditor = new Quill('.snow-container .compose-editor', {
    modules: {
      toolbar: '.compose-quill-toolbar'
    },
    placeholder: 'Add Description..... ',
    theme: 'snow'
  });

  //Assigner Comment Quill editor
  var commentEditor = new Quill('.snow-container .comment-editor', {
    modules: {
      toolbar: '.comment-quill-toolbar'
    },
    placeholder: 'Write a Comment...',
    theme: 'snow'
  });

  // **************Sidebar Left**************//
  // -----------------------------------------

  // Main menu toggle should hide app menu
  $('.menu-toggle').on('click', function () {
    sideBarLeft.removeClass('show');
    appContentOverlay.removeClass('show');
    todoNewTasksidebar.removeClass('show');
  });

  //on click of app overlay removeclass show from sidebar left and overlay
  appContentOverlay.on('click', function () {
    sideBarLeft.removeClass('show');
    appContentOverlay.removeClass('show');
  });

  // Add class active on click of sidebar menu's filters
  todoAppMenu.find(".list-group a").on('click', function () {
    var $this = $(this);
    todoAppMenu.find(".active").removeClass('active');
    $this.addClass("active")
    // if active class available icon color primary blue else gray
    if ($this.hasClass('active')) {
      $this.find(".livicon-evo").updateLiviconEvo({
        strokeColor: '#5A8DEE'
      });
      todoAppMenu.find(".list-group a").not(".active").find(".livicon-evo").updateLiviconEvo({
        strokeColor: '#475f7b'
      });
    }
  });

  //On compose btn click of compose mail visible and sidebar left hide
  $('.add-task-btn').on('click', function () {
    //show class add on new task sidebar,overlay
    todoNewTasksidebar.addClass('show');
    appContentOverlay.addClass('show');
    sideBarLeft.removeClass('show');
    taskTitle.focus();
    //d-none add on avatar and remove from avatar-content
    avatarUserImage.addClass("d-none");
    assignedAvatarContent.removeClass("d-none");
    //select2 value null assign
    selectUsersName.val(null).trigger('change');
    selectAssignLable.val(null).trigger('change');
    //update button has add class d-none remove from add TODO
    updateTodo.addClass("d-none");
    addTodo.removeClass("d-none");
    //mark complete btn should hide & new task title will visible
    markCompleteBtn.addClass("d-none");
    newTaskTitle.removeClass("d-none");
    //Input field Value empty
    taskTitle.val("");
    var compose_editor = $(".compose-editor .ql-editor");
    compose_editor[0].innerHTML = "";
    var comment_editor = $(".comment-editor .ql-editor");
    comment_editor[0].innerHTML = "";
    selectAssignLable.attr("disabled", "true");
  });

 
  
  // ************Rightside content************//
  // -----------------------------------------

  // Search filter for task list
  $(document).on("keyup", ".todo-search", function () {
    todoItem = $(".todo-item");
    $('.todo-item').css('animation', 'none')
    var value = $(this).val().toLowerCase();
    if (value != "") {
      todoItem.filter(function () {
        $(this).toggle($(this).text().toLowerCase().includes(value));
      });
      var tbl_row = $(".todo-item:visible").length; //here tbl_test is table name

      //Check if table has row or not
      if (tbl_row == 0) {
        if (!noResults.hasClass('show')) {
          noResults.addClass('show');
        }
      }
      else {
        noResults.removeClass('show');
      }
    }
    else {
      // If filter box is empty
      todoItem.show();
      if (noResults.hasClass('show')) {
        noResults.removeClass('show');
      }
    }
  });
  // on Todo Item click show data in sidebar
  var globalThis = ""; // Global variable use in multiple function
  todoTaskListWrapper.on('click', '.todo-item', function (e) {
    var $this = $(this);
    globalThis = $this;

    todoNewTasksidebar.addClass('show');
    appContentOverlay.addClass('show');

    var todoTitle = $this.find(".todo-title").text();
    taskTitle.val(todoTitle);
    var compose_editor = $(".compose-editor .ql-editor");
    compose_editor[0].innerHTML = todoTitle;

    // if avatar is available
    if ($this.find(".avatar img").length) {
      avatarUserImage.removeClass("d-none");
      assignedAvatarContent.addClass("d-none");
    }
    else {
      avatarUserImage.addClass("d-none");
      assignedAvatarContent.removeClass("d-none");
    }
    //current task's image source assign to variable
    var avatarSrc = $this.find(".avatar img").attr('src');

    avatarUserImage.attr("src", avatarSrc);
    var assignName = $this.attr('data-name');

    $(".select2-users-name").val(assignName).trigger('change');

    // badge selected value check
    if ($(this).find('.badge').length) {
      //if badge available in current task
      var badgevalAll = [];
      var selected = $(this).find('.badge');

      selected.each(function () {
        var badgeVal = $(this).text();
        badgevalAll.push(badgeVal);
        selectAssignLable.val(badgevalAll).trigger("change");
      });
    }
    else {
      selectAssignLable.val(null).trigger("change");
    }
    // update button has remove class d-none & add class d-none in add todo button
    updateTodo.removeClass("d-none");
    addTodo.addClass("d-none");
    markCompleteBtn.removeClass("d-none");
    newTaskTitle.addClass("d-none");

  }).on('click', '.todo-item-favorite', function (e) {
    e.stopPropagation();
    $(this).toggleClass("warning");
    $(this).find("i").toggleClass("bxs-star");
  }).on('click', '.todo-item-delete', function (e) {
    e.stopPropagation();
    $(this).closest('.todo-item').remove();
  }).on('click', '.checkbox', function (e) {
    e.stopPropagation();
  });

  // Complete task strike through
  todoTaskListWrapper.on('click', ".todo-item .checkbox-input", function (e) {
    $(this).closest('.todo-item').toggleClass("completed");
  });

  // Complete button click action
  markCompleteBtn.on("click", function () {
    globalThis.addClass("completed");
    globalThis.find(".checkbox-input").prop("checked", true);
    selectAssignLable.attr("disabled", "true");
  });

  // Todo sidebar toggle
  $('.sidebar-toggle').on('click', function (e) {
    e.stopPropagation();
    sideBarLeft.toggleClass('show');
    appContentOverlay.addClass('show');
  });

  // sorting task list item
  $(".ascending").on("click", function () {
    todoItem = $(".todo-item");
    $('.todo-item').css('animation', 'none')
    todoItem.sort(sort_li).appendTo(todoTaskListWrapper);
    function sort_li(a, b) {
      return ($(b).find('.todo-title').text()) < ($(a).find('.todo-title').text()) ? 1 : -1;
    }
  });

  // descending sorting
  $(".descending").on("click", function () {
    todoItem = $(".todo-item");
    $('.todo-item').css('animation', 'none')
    todoItem.sort(sort_li).appendTo(todoTaskListWrapper);
    function sort_li(a, b) {
      return ($(b).find('.todo-title').text()) > ($(a).find('.todo-title').text()) ? 1 : -1;
    }
  });

      
});

$(window).on("resize", function () {
  // remove show classes from sidebar and overlay if size is > 992
  if ($(window).width() > 992) {
    if (appContentOverlay.hasClass('show')) {
      sideBarLeft.removeClass('show');
      appContentOverlay.removeClass('show');
      todoNewTasksidebar.removeClass("show");
    }
  }
});

    
// Single Date Picker
  // -----------------
  $('.single-daterange').daterangepicker({
    singleDatePicker: true,
    showDropdowns: true,
    minYear: 1990,
    maxYear: parseInt(moment().format('YYYY'), 10)
  });

// Scroll add inventory page 
  // -----------------
$(".right-scroll-list li a[href^='#']").click(function(e) {
	e.preventDefault();
	
	var position = $($(this).attr("href")).offset().top - 140;

	$("body, html").animate({
		scrollTop: position
	} /* speed */ );
});

// Scroll add inventory hidden file
  // ----------------- 

$(function () {
    $("#customSwitch13").click(function () {
        if ($(this).is("click")) {
            $("#price-hidden").show();
        } else {
            $("#price-hidden").hide();
        }
    });
});

$(document).ready(function() {
    
    // Special Price.
    
    $("#customSwitch13").click(function(){
        $('.price-hidden').toggleClass("price-show-bl");
    });
    
    // Financing Special
    
    $("#customSwitch11").click(function(){
        $('.price-hidden-fin').toggleClass("price-show-bl");
    });
    
    // Limited Time
     $('#dateselector').change(function(){
        $('.limit-time-block').hide();
        $('#' + $(this).val()).show();
      });
        
    // Options & Features all checked
    
     $("#checkbox0").click(function () {
        $('#all-check-list  li input:checkbox').not(this).prop('checked', this.checked);
     });
         
    // Options & Features comfort all checked
    
     $("#checkboxcom").click(function () {
        $('#comfort li input:checkbox').not(this).prop('checked', this.checked);
     });
         
    // Options & Features Exterior all checked
    
     $("#checkboxext").click(function () {
        $('#exterior li input:checkbox').not(this).prop('checked', this.checked);
     });
    // Options & Features Entertainment all checked
    
     $("#checkboxent").click(function () {
        $('#entertainment li input:checkbox').not(this).prop('checked', this.checked);
     });
    // Options & Features Power Option all checked
    
     $("#checkboxpow").click(function () {
        $('#power-option li input:checkbox').not(this).prop('checked', this.checked);
     });
    
    // Options & Features Safety Security all checked
     $("#checkboxsaf").click(function () {
        $('#safety-security li input:checkbox').not(this).prop('checked', this.checked);
     });
    
    // Options & Features Seating all checked
     $("#checkboxseat").click(function () {
        $('#seating li input:checkbox').not(this).prop('checked', this.checked);
     });
    
    // Options & Features Warranty all checked
     $("#checkboxwarr").click(function () {
        $('#warranty li input:checkbox').not(this).prop('checked', this.checked);
     });
    
     $('.bg-active-box').on('click', function(){
        $('.bg-active-box.active-color-c').removeClass('active-color-c');
        $(this).addClass('active-color-c');
    });
    $('.bg-active-box1').on('click', function(){
        $('.bg-active-box1.active-color-s').removeClass('active-color-s');
        $(this).addClass('active-color-s');
    });
    $( ".bg-active-box" ).click(function() {
      var color = $( this ).css( "background-color" );
//              $( ".colo-print" ).html( "That div is " + color + "." );
          $( ".colo-print:input" ).val( color);
    });
    
        $('.custom-options').click(function(){
        $('.active1').removeClass('active1');
        $(this).addClass('active1');
        $('.standard-equipment').removeClass().addClass('standard-equipment').addClass(this.id);
        $('.additonal').removeClass().addClass('additonal').addClass(this.id);
        $('.custom-options-1').removeClass().addClass('custom-options-1').addClass(this.id);
        $('.add-custom-options-1').removeClass().addClass('add-custom-options-1').addClass(this.id);
    });
    
});

/*****************************************************
*               Remove All Thumbnails                *
*****************************************************/
//Dropzone.options.dpzRemoveAllThumb = {
//  paramName: "file", // The name that will be used to transfer the file
//  maxFilesize: 1, // MB
//  init: function () {
//
//    // Using a closure.
//    var _this = this;
//
//    // Setup the observer for the button.
//    $("#clear-dropzone").on("click", function () {
//      // Using "_this" here, because "this" doesn't point to the dropzone anymore
//      _this.removeAllFiles();
//        myDropzone.removeFile(file);    
//      // If you want to cancel uploads as well, you
//      // could also call _this.removeAllFiles(true);
//    });
//  }
//}

$(document).ready(function(){
 $('.sortable').sortable();
    
    $('.gallery-box input').click(function(){
       
       var totalCheckboxes =$('.gallery-box  input:checked').length;

	if($(this).is(":checked")) {
        $('.gallery-box-add').addClass('gallery-select-box');
    } else {
		if(totalCheckboxes==0){
			$('.gallery-box-add').removeClass('gallery-select-box');
		}
       
    }
});
   
});

 $('#file-dropzone').dropzone({ 
    url: "/upload",
    maxFilesize: 100,
    paramName: "uploadfile",
    maxThumbnailFilesize: 99999,
    previewsContainer: '.visualizacao', 
    previewTemplate : $('.preview').html(),
    init: function() {
      this.on('completemultiple', function(file, json) {
       $('.sortable').sortable('enable');
      });
      this.on('success', function(file, json) {
        alert('aa');
      });
      
      this.on('addedfile', function(file) {
       
      });
      
      this.on('drop', function(file) {
        console.log('File',file)
      }); 
    }
  });

	/****************************************************
	*				 Slider With Input				*
	****************************************************/
$(document).ready(function () {
    
/*****************
    Downpayment
  *********************/  
    
var sliderWithInput = document.getElementById('slider-with-input');

noUiSlider.create(sliderWithInput, {
    start: [40],
     step: 1,
    connect:'lower',
    range: {
        'min': [0],
        'max': [100]
    }
});
    
var sliderWithInputElement = document.getElementById('slider-With-Input');
sliderWithInput.noUiSlider.on('update', function (values, handle) {
    sliderWithInputElement.innerHTML = values[handle];
});
 
/*****************
    Interest Rate
  *********************/  
    
var interestWithInput = document.getElementById('interest-with-input');

noUiSlider.create(interestWithInput, {
    start: [20],
     step: 1,
    connect:'lower',
    range: {
        'min': [0],
        'max': [50]
    }
});
var interestWithInputElement = document.getElementById('interest-With-Input');
interestWithInput.noUiSlider.on('update', function (values, handle) {
    interestWithInputElement.innerHTML = values[handle];
})
    /*****************
    Term Rate
  *********************/  
    
var termWithInput = document.getElementById('term-with-input');

noUiSlider.create(termWithInput, {
    start: [60],
     step: 1,
    connect:'lower',
    range: {
        'min': [0],
        'max': [96]
    }
});
    
var termWithInputElement = document.getElementById('term-With-Input');
termWithInput.noUiSlider.on('update', function (values, handle) {
    termWithInputElement.innerHTML = values[handle];
});    

    });

/*=========================================================================================
    File Name: Form-Repeater.js
    Description: form repeater page specific js
==========================================================================================*/

$(document).ready(function () {
  // form repeater jquery
  $('.contact-repeater, .additional-contact-repeater').repeater({
    show: function () {
      $(this).slideDown();
    },
    hide: function (deleteElement) {
      if (confirm('Are you sure you want to delete this element?')) {
        $(this).slideUp(deleteElement);
      }
    }
  });

}); 

/*=========================================================================================
    File Name: wizard-steps.js
    Description: wizard steps page specific js
==========================================================================================*/
//    Wizard tabs with icons setup
// ------------------------------
$(".wizard-horizontal").steps({
  headerTag: "h6",
  bodyTag: "fieldset",
  transitionEffect: "fade",
  titleTemplate: '<span class="step">#index#</span> #title#',
  labels: {
    finish: 'Submit'
  },
  onFinished: function (event, currentIndex) {
    alert("Form submitted.");
  }
});
//        vertical Wizard       //
// ------------------------------
$(".wizard-vertical").steps({
  headerTag: "h3",
  bodyTag: "fieldset",
  transitionEffect: "fade",
  enableAllSteps: true,
  stepsOrientation: "vertical",
  labels: {
    finish: 'Submit'
  },
  onFinished: function (event, currentIndex) {
    alert("Form submitted.");
  }
});


//       Validate steps wizard //
// -----------------------------
// Show form
var stepsValidation = $(".wizard-validation");
var form = stepsValidation.show();

stepsValidation.steps({
  headerTag: "h6",
  bodyTag: "fieldset",
  transitionEffect: "fade",
  titleTemplate: '<span class="step">#index#</span> #title#',
  labels: {
    finish: 'Submit'
  },
  onStepChanging: function (event, currentIndex, newIndex) {
    // Allways allow previous action even if the current form is not valid!
    if (currentIndex > newIndex) {
      return true;
    }
    form.validate().settings.ignore = ":disabled,:hidden";
    return form.valid();
  },
  onFinishing: function (event, currentIndex) {
    form.validate().settings.ignore = ":disabled";
    return form.valid();
  },
  onFinished: function (event, currentIndex) {
    alert("Submitted!");
  }
});

// Initialize validation
stepsValidation.validate({
  ignore: 'input[type=hidden]', // ignore hidden fields
  errorClass: 'danger',
  successClass: 'success',
  highlight: function (element, errorClass) {
    $(element).removeClass(errorClass);
  },
  unhighlight: function (element, errorClass) {
    $(element).removeClass(errorClass);
  },
  errorPlacement: function (error, element) {
    error.insertAfter(element);
  },
  rules: {
    email: {
      email: true
    }
  }
});
// live Icon color change on state change
$(document).ready(function () {
  $(".current").find(".step-icon").addClass("bx bx-time-five");
  $(".current").find(".fonticon-wrap .livicon-evo").updateLiviconEvo({
    strokeColor: '#5A8DEE'
  });
});
// Icon change on state
// if click on next button icon change
$(".actions [href='#next']").click(function () {
  $(".done").find(".step-icon").removeClass("bx bx-time-five").addClass("bx bx-check-circle");
  $(".current").find(".step-icon").removeClass("bx bx-check-circle").addClass("bx bx-time-five");
  // live icon color change on next button's on click
  $(".current").find(".fonticon-wrap .livicon-evo").updateLiviconEvo({
    strokeColor: '#5A8DEE'
  });
  $(".current").prev("li").find(".fonticon-wrap .livicon-evo").updateLiviconEvo({
    strokeColor: '#39DA8A'
  });
});
$(".actions [href='#previous']").click(function () {
  // live icon color change on next button's on click
  $(".current").find(".fonticon-wrap .livicon-evo").updateLiviconEvo({
    strokeColor: '#5A8DEE'
  });
  $(".current").next("li").find(".fonticon-wrap .livicon-evo").updateLiviconEvo({
    strokeColor: '#adb5bd'
  });
});
// if click on  submit   button icon change
$(".actions [href='#finish']").click(function () {
  $(".done").find(".step-icon").removeClass("bx-time-five").addClass("bx bx-check-circle");
  $(".last.current.done").find(".fonticon-wrap .livicon-evo").updateLiviconEvo({
    strokeColor: '#39DA8A'
  });
});
// add primary btn class
$('.actions a[role="menuitem"]').addClass("btn btn-primary");
$('.icon-tab [role="menuitem"]').addClass("glow ");
$('.wizard-vertical [role="menuitem"]').removeClass("btn-primary").addClass("btn-light-primary");


/*=========================================================================================
    File Name: list- inventory
    Description: list inventory
==========================================================================================*/

(function (window, document, $) {

  var $primary = '#5A8DEE';
  var $danger = '#FF5B5C';
  var $warning = '#FDAC41';
  var $info = '#00CFDD';
  var $gray_light = '#828D99';

  // table extended transactions
   $('#table-extended-transactions').DataTable({
    "responsive":true,
    "searching": true,
    "lengthChange": true,
    "paging": true,
    "bInfo": true,
    "scrollX": true,
    "lengthMenu": [[ 25, 50, 100, 200, -1], [ 25, 50, 100, 200, "All"]],
    "columnDefs": [
    {
        'targets': 0,
        'render': function (data, type, full, meta) {
          if (type === 'display') {
            data = '<div class="checkbox"><input type="checkbox" class="dt-checkboxes"><label></label></div>'; //body checkbox
          }
          return data;
        },
        'checkboxes': {
          'selectRow': true,
          'selectAllRender': '<div class="checkbox"><input type="checkbox" class="dt-checkboxes" ><label></label></div >'  //head checkbox
        }
      },
    { "orderable": false, "targets": [ 0, 1, 2, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20 ] }   
    ],
    dom: "<'row'<'col-sm-4'B><'col-sm-4 serach-center'f><'col-sm-4'>>" +
                  "<'row'<'col-sm-12'tr>>" +
                  "<'row content-wrapper pt-0'<'col-sm-4'i><'col-sm-6 text-right d-list-box'l><'col-sm-2 text-right'p>>",   
    'select': 'multi',
    'order': [[1, 'asc']]
  });
})(window, document, jQuery);


(function (window, document, $) {

  var $primary = '#5A8DEE';
  var $danger = '#FF5B5C';
  var $warning = '#FDAC41';
  var $info = '#00CFDD';
  var $gray_light = '#828D99';

  // table extended transactions
   $('#table-extended-transactions-grid').DataTable({
    "responsive":true,
    "searching": false,
    "lengthChange": true,
    "paging": true,
    "bInfo": true,
    "scrollX": false,
     "scrollY":"60vh",
        "scrollCollapse": true,   
    "lengthMenu": [[ 5, 50, 100, 200, -1], [ 5, 50, 100, 200, "All"]],
    "columnDefs": [
    {
        'targets': 0,
      },
    { "orderable": false, "targets": [ 0, 1 ] }   
    ],
    dom: "<'row'<'col-sm-0'B><'col-sm-12 'f><'col-sm-0'>>" +
                  "<'row'<'col-sm-12'tr>>" +
                  "<'row content-wrapper space-set-list fixed-bottom-list-box p-0'<'col-sm-4'i><'col-sm-4 text-right d-grid-box'l><'col-sm-4 text-right c-grid-list'p>>",   
    'select': 'multi',
    'order': [[1, 'asc']]
  });
     
})(window, document, jQuery);



/*=========================================================================================
	File Name: list page drag drop code
==========================================================================================*/

$(document).ready(function () {
  // With Handles

  dragula([document.getElementById("handle-list-1"), document.getElementById("handle-list-2")], {
    moves: function (el, container, handle) {
      return handle.classList.contains('handle');
    }
  }); 
    
    // dropdown open 
    
    $('.drag-list-block span').click(function() {
    $(this).parent().toggleClass('open');
    });
    
    // dropdown close save button
    
    $('.click-btn-drag').click(function() {
    $('.drag-list-block').removeClass('open'); 
    });
    
    // get date value code 
    
    $('.click-btn-drag').click(function() {
    var sel = $('#handle-list-1 input[type=checkbox]:checked').map(function(_, el) {
        return $(el).val();
    }).get();
    
    alert(sel);
})

  
       
});


$(document).ready( function(){
    
    $.fn.editable.defaults.mode = 'Popup';
    $.fn.editableform.buttons = 
      '<button type="submit" class="btn btn-primary editable-submit">'+
        '<i class="ficon bx bx-check " aria-hidden="true"></i>'+
      '</button>'+
      '<button type="button" class="btn btn-secondary btn-sm editable-cancel">'+
        '<i class="ficon bx bx-x" aria-hidden="true"></i>'+
      '</button>';  
    $('#price1, #price2, #price3, #price4, #price5, #price6, #price7, #price8, #price9, #price10, #price12, #price13, #price14, #price14, #price15').editable({
           url: '/post',
           type: 'number',
           pk: 1,
           name: 'Price',
           title: 'price'
        
    });
    $('#sp-price1, #sp-price2, #sp-price3, #sp-price4, #sp-price5, #sp-price6, #sp-price7, #sp-price8, #sp-price9, #sp-price10, #sp-price12, #sp-price13, #sp-price14, #sp-price14, #sp-price15').editable({
           url: '/post',
           type: 'number',
           pk: 1,
           name: 'Special Price',
           title: 'Special price'
        
    });
      


    // Disable all input fields
    $(".editable-input").prop("disabled", true);
    
    // Hide all close icons
    $(".editable-icon-2").hide();
    
    $(".editable-icon").on("click", function() {
        $(this).hide();
        $(this).parent().find(".editable-icon-2").show();
        var toedit = $(this).parent().find("input");
        toedit.prop( "disabled", false );
        $(this).parent().find(".editable-icon-2").show();
        var toedit = $(this).parent().find("select");
        toedit.prop( "disabled", false );
        $(this).parent().parent().find(".input-group").addClass("border");
    });
    
    $(".editable-icon-2").on("click", function() {
        $(this).hide();
        $(this).parent().find(".editable-icon").show();
        var toedit = $(this).parent().find("input");
        toedit.prop( "disabled", true );
        $(this).parent().find(".editable-icon").show();
        var toedit = $(this).parent().find("select");
        toedit.prop( "disabled", true );
        $(this).parent().parent().find(".input-group").removeClass("border");
    });
     
});


// checked box click add bar code box

$(document).ready(function(){ 
    $('.check-bulk thead .dt-checkboxes').change(function(){
        
    if($(this).is(":checked")) {
        $('.set-dable-layer').addClass('dislable-block-t');
    } else  {
        $('.set-dable-layer').removeClass('dislable-block-t');
    }
});
    $('.check-bulk   tbody .dt-checkboxes').click(function(){
       var totalCheckboxes =$('.check-bulk  tbody .dt-checkboxes:checked').length;

	if($(this).is(":checked")) {
        $('.set-dable-layer').addClass('dislable-block-t');
    } else {
		if(totalCheckboxes==0){
			$('.set-dable-layer').removeClass('dislable-block-t');
		}
       
    }
}); 
    
    $('.close-btn').click(function(){
    $('.set-dable-layer').removeClass('dislable-block-t');
    $('.check-bulk .dt-checkboxes').prop('checked',false);    
});        
    

    
    
});

/*=========================================================================================
	Edit User page upload image jquery 
==========================================================================================*/
$(document).ready(function(){ 
function readURL(input) {
        if (input.files && input.files[0]) {
          var reader = new FileReader();
          reader.onload = function (e) {
            $('#imagePreview').css('background-image', 'url(' + e.target.result + ')');
            $('#imagePreview').hide();
            $('#imagePreview').fadeIn(650);
          }
          reader.readAsDataURL(input.files[0]);
        }
      }

      $("#imageUpload").change(function () {
        readURL(this);
      });

      
       var body = document.getElementsByTagName('body')[0];
       var removeLoading = function () {
       // In a production application you would remove the loading class when your
       // application is initialized and ready to go.  Here we just artificially wait
       // 3 seconds before removing the class.
       setTimeout(function () {
       body.className = body.className.replace(/loading/, '');
       }, 500);
       };
       removeLoading(); 
    });

/*=========================================================================================
	Edit User page upload image jquery 
==========================================================================================*/
$(document).ready(function(){ 
function readURL(input) {
        if (input.files && input.files[0]) {
          var reader = new FileReader();
          reader.onload = function (e) {
            $('#imagePreview1').css('background-image', 'url(' + e.target.result + ')');
            $('#imagePreview1').hide();
            $('#imagePreview1').fadeIn(650);
          }
          reader.readAsDataURL(input.files[0]);
        }
      }

      $("#imageUpload1").change(function () {
        readURL(this);
      });

      
       var body = document.getElementsByTagName('body')[0];
       var removeLoading = function () {
       // In a production application you would remove the loading class when your
       // application is initialized and ready to go.  Here we just artificially wait
       // 3 seconds before removing the class.
       setTimeout(function () {
       body.className = body.className.replace(/loading/, '');
       }, 500);
       };
       removeLoading(); 
    });

/*=========================================================================================
	Edit User page upload image jquery 
==========================================================================================*/
$(document).ready(function(){ 
function readURL(input) {
        if (input.files && input.files[0]) {
          var reader = new FileReader();
          reader.onload = function (e) {
            $('#imagePreview2').css('background-image', 'url(' + e.target.result + ')');
            $('#imagePreview2').hide();
            $('#imagePreview2').fadeIn(650);
          }
          reader.readAsDataURL(input.files[0]);
        }
      }

      $("#imageUpload2").change(function () {
        readURL(this);
      });

      
       var body = document.getElementsByTagName('body')[0];
       var removeLoading = function () {
       // In a production application you would remove the loading class when your
       // application is initialized and ready to go.  Here we just artificially wait
       // 3 seconds before removing the class.
       setTimeout(function () {
       body.className = body.className.replace(/loading/, '');
       }, 500);
       };
       removeLoading(); 
    });

/*=========================================================================================
	Edit User page upload image jquery 
==========================================================================================*/
$(document).ready(function(){ 
function readURL(input) {
        if (input.files && input.files[0]) {
          var reader = new FileReader();
          reader.onload = function (e) {
            $('#imagePreview3').css('background-image', 'url(' + e.target.result + ')');
            $('#imagePreview3').hide();
            $('#imagePreview3').fadeIn(650);
          }
          reader.readAsDataURL(input.files[0]);
        }
      }

      $("#imageUpload3").change(function () {
        readURL(this);
      });

      
       var body = document.getElementsByTagName('body')[0];
       var removeLoading = function () {
       // In a production application you would remove the loading class when your
       // application is initialized and ready to go.  Here we just artificially wait
       // 3 seconds before removing the class.
       setTimeout(function () {
       body.className = body.className.replace(/loading/, '');
       }, 500);
       };
       removeLoading(); 
    });


/*=========================================================================================
    File Name: script.js
    Description: List Grid view 

==========================================================================================*/

$(function () {
  "use strict";

  // Sidebar scrollbar
  if ($('.sidebar-menu-list').length > 0) {
    var sidebarMenuList = new PerfectScrollbar('.sidebar-menu-list', {
      theme: "dark",
      wheelPropagation: false
    });
  }

//  New task scrollbar
  if (todoNewTasksidebar.length > 0) {
    var todo_new_task_sidebar = new PerfectScrollbar('.todo-new-task-sidebar', {
      theme: "dark",
      wheelPropagation: false
    });
  }
 
  // Task list scrollbar
  if ($('.list-right-task-list').length > 0) {
    var sidebar_todo = new PerfectScrollbar('.list-right-task-list', {
      theme: "dark",
      wheelPropagation: false
    });
  }
    // Overlay list scrollbar
  if ($('.list-right-task-overlay').length > 0) {
    var sidebar_todo = new PerfectScrollbar('.list-right-task-overlay', {
      theme: "dark",
      wheelPropagation: false
    });
  } 
    // Syndication list scrollbar
  if ($('.list-right-task-syndication').length > 0) {
    var sidebar_todo = new PerfectScrollbar('.list-right-task-syndication', {
      theme: "dark",
      wheelPropagation: false
    });
  } 
// Locations list scrollbar
  if ($('.list-right-task-locations').length > 0) {
    var sidebar_todo = new PerfectScrollbar('.list-right-task-locations', {
      theme: "dark",
      wheelPropagation: false
    });
  }
});
 
//On compose btn click of compose mail visible and sidebar left hide
  $('.add-right-btn').on('click', function () {
    //show class add on new task sidebar,overlay
    todoNewTasksidebar.addClass('show');
    appContentOverlay.addClass('show');
    taskTitle.focus();
  });

 // On sidebar close click hide sidebarleft and overlay
  $(".close-icon").on('click', function () {
    todoNewTasksidebar.removeClass('show');
    appContentOverlay.removeClass('show');
  });


$(window).on("resize", function () {
  // remove show classes from sidebar and overlay if size is > 992
  if ($(window).width() > 992) {
    if (appContentOverlay.hasClass('show')) {
      sideBarLeft.removeClass('show');
      appContentOverlay.removeClass('show');
      todoNewTasksidebar.removeClass("show");
    }
  }
}); 

//chart start right bar content

$(window).on("load", function () {

  var $primary = '#5A8DEE';
  var $success = '#39DA8A';
  var $danger = '#FF5B5C';
  var $warning = '#FDAC41';
    
// Primary Line Chart
  // -----------------------------
  var primaryLineChartOption = {
    chart: {
      height: 40,
      // width: 180, 
      type: 'line',
      toolbar: {
        show: false
      },
      sparkline: {
        enabled: true,
      },
    },
    grid: {
      show: false,
      padding: {
        bottom: 5,
        top: 5,
        left: 10,
        right: 0
      }
    },
    colors: [$primary],
    dataLabels: {
      enabled: false,
    },
    stroke: {
      width: 3,
      curve: 'smooth'
    },
    series: [{
      data: [50, 100, 0, 60, 20, 30]
    }],
    fill: {
      type: 'gradient',
      gradient: {
        shade: 'dark',
        type: "horizontal",
        gradientToColors: [$primary],
        opacityFrom: 0,
        opacityTo: 0.9,
        stops: [0, 30, 70, 100]
      }
    },
    xaxis: {
      show: false,
      labels: {
        show: false
      },
      axisBorder: {
        show: false
      }
    },
    yaxis: {
      show: false
    },
  }

  var primaryLineChart = new ApexCharts(
    document.querySelector("#primary-line-chart"),
    primaryLineChartOption
  );
  primaryLineChart.render();
    
    // Growth Radial Chart
  // --------------------
  var growthChartOptions = {
    chart: {
      height: 230,
      type: 'radialBar',       
      sparkline: {
        show: true
      }
    },
    grid: {
      show: false,
    },
    plotOptions: {
      radialBar: {
        size: 100,
        startAngle: -135,
        endAngle: 135,
        offsetY: 40,
        hollow: {
          size: '60%',
        },
        track: {
          strokeWidth: '90%',
          background: '#fff'
        },
        dataLabels: {
          value: {
            offsetY: -10,
            color: '#475f7b',
            fontSize: '26px'
          },
          name: {
            fontSize: '15px',
            color: "#596778",
            offsetY: 30
          },
        }
      },
    },
    colors: [$danger],
    fill: {
      type: 'gradient',
      gradient: {
        shade: 'dark',
        type: 'horizontal',
        shadeIntensity: 0.5,
        gradientToColors: [$primary],
        inverseColors: true,
        opacityFrom: 1,
        opacityTo: 1,
        stops: [0, 100]
      },
    },
    stroke: {
      dashArray: 3
    },
    series: [78],
    labels: ['Vehicle Health'],  
  }

  var growthChart = new ApexCharts(
    document.querySelector("#growth-Chart"),
    growthChartOptions
  );

  growthChart.render();
    
// Warning Line Chart
  // -----------------------------
  var warningLineChartOption = {
    chart: {
      height: 40,
      // width: 90,
      type: 'line',
      toolbar: {
        show: false
      },
      sparkline: {
        enabled: true,
      },
    },
    grid: {
      show: false,
      padding: {
        bottom: 5,
        top: 5,
        left: 10,
        right: 0
      }
    },
    colors: [$warning],
    dataLabels: {
      enabled: false,
    },
    stroke: {
      width: 3,
      curve: 'smooth'
    },
    series: [{
      data: [30, 60, 30, 80, 20, 70]
    }],
    fill: {
      type: 'gradient',
      gradient: {
        shade: 'dark',
        type: "horizontal",
        gradientToColors: [$warning],
        opacityFrom: 0,
        opacityTo: 0.9,
        stops: [0, 30, 70, 100]
      }
    },
    xaxis: {
      show: false,
      labels: {
        show: false
      },
      axisBorder: {
        show: false
      }
    },
    yaxis: {
      show: false
    },
  }

  var warningLineChart = new ApexCharts(
    document.querySelector("#warning-line-chart"),
    warningLineChartOption
  );
  warningLineChart.render();
    
    // danger Line Chart
  // -----------------------------
  var dangerLineChartOption = {
    chart: {
      height: 40,
      // width: 180, 
      type: 'line',
      toolbar: {
        show: false
      },
      sparkline: {
        enabled: true,
      },
    },
    grid: {
      show: false,
      padding: {
        bottom: 5,
        top: 5,
        left: 10,
        right: 0
      }
    },
    colors: [$danger],
    dataLabels: {
      enabled: false,
    },
    stroke: {
      width: 3,
      curve: 'smooth'
    },
    series: [{
      data: [50, 100, 0, 60, 20, 30]
    }],
    fill: {
      type: 'gradient',
      gradient: {
        shade: 'dark',
        type: "horizontal",
        gradientToColors: [$danger],
        opacityFrom: 0,
        opacityTo: 0.9,
        stops: [0, 30, 70, 100]
      }
    },
    xaxis: {
      show: false,
      labels: {
        show: false
      },
      axisBorder: {
        show: false
      }
    },
    yaxis: {
      show: false
    },
  }

  var dangerLineChart = new ApexCharts(
    document.querySelector("#danger-line-chart"),
    dangerLineChartOption
  );
  dangerLineChart.render();
    
});

/*=========================================================================================
	File Name: team.html
    
	TEAM PAGE CODE START
==========================================================================================*/

(function (window, document, $) {

  var $primary = '#5A8DEE';
  var $danger = '#FF5B5C';
  var $warning = '#FDAC41';
  var $info = '#00CFDD';
  var $gray_light = '#828D99';

  // table extended transactions
   $('#table-extended-team').DataTable({
    "responsive":true,
    "searching": true,
    "lengthChange": true,
    "paging": true,
    "bInfo": true,
    "scrollX": false,
    "lengthMenu": [[ 25, 50, 100, 200, -1], [ 25, 50, 100, 200, "All"]],
    "columnDefs": [
    {
        'targets': 0,
        'render': function (data, type, full, meta) {
          if (type === 'display') {
            data = '<div class="checkbox"><input type="checkbox" class="dt-checkboxes"><label></label></div>'; //body checkbox
          }
          return data;
        },
        'checkboxes': {
          'selectRow': true,
          'selectAllRender': '<div class="checkbox"><input type="checkbox" class="dt-checkboxes" ><label></label></div >'  //head checkbox
        }
      },
    { "orderable": false, "targets": [ 0, 1, 2, 5, 6,  ] }   
    ],
    dom: "<'row'<'col-sm-4'B><'col-sm-4 serach-center'f><'col-sm-4'>>" +
                  "<'row'<'col-sm-12'tr>>" +
                  "<'row content-wrapper pt-0'<'col-sm-4'i><'col-sm-6 text-right d-list-box'l><'col-sm-2 text-right'p>>",   
    'select': 'multi',
    'order': [[1, 'asc']]
  });
})(window, document, jQuery); 

/*=========================================================================================
	File Name: team.html
    
    REVIEW PAGE CODE START
==========================================================================================*/

(function (window, document, $) {

  var $primary = '#5A8DEE';
  var $danger = '#FF5B5C';
  var $warning = '#FDAC41';
  var $info = '#00CFDD';
  var $gray_light = '#828D99';

  // table extended transactions
   $('#table-extended-Review').DataTable({
    "responsive":true,
    "searching": true,
    "lengthChange": true,
    "paging": true,
    "bInfo": true,
    "scrollX": false,
    "lengthMenu": [[ 25, 50, 100, 200, -1], [ 25, 50, 100, 200, "All"]],
    "columnDefs": [
    {
        'targets': 0,
        'render': function (data, type, full, meta) {
          if (type === 'display') {
            data = '<div class="checkbox"><input type="checkbox" class="dt-checkboxes"><label></label></div>'; //body checkbox
          }
          return data;
        },
        'checkboxes': {
          'selectRow': true,
          'selectAllRender': '<div class="checkbox"><input type="checkbox" class="dt-checkboxes" ><label></label></div >'  //head checkbox
        }
      },
    { "orderable": false, "targets": [ 0, 1, 2, 4, 5, 6, 7, 8, 9, 10  ] }   
    ],
    dom: "<'row'<'col-sm-4'B><'col-sm-4 serach-center'f><'col-sm-4'>>" +
                  "<'row'<'col-sm-12'tr>>" +
                  "<'row content-wrapper pt-0'<'col-sm-4'i><'col-sm-6 text-right d-list-box'l><'col-sm-2 text-right'p>>",   
    'select': 'multi',
    'order': [[1, 'asc']]
  });
})(window, document, jQuery);

/*=========================================================================================
File Name: leads.html
    
LEADS PAGE CODE START
==========================================================================================*/

(function (window, document, $) {

  var $primary = '#5A8DEE';
  var $danger = '#FF5B5C';
  var $warning = '#FDAC41';
  var $info = '#00CFDD';
  var $gray_light = '#828D99';

  // table extended transactions
   $('#table-extended-leads').DataTable({
    "responsive":true,
    "searching": true,
    "lengthChange": true,
    "paging": true,
    "bInfo": true,
    "scrollX": false,
    "lengthMenu": [[ 25, 50, 100, 200, -1], [ 25, 50, 100, 200, "All"]],
    "columnDefs": [
    {
        'targets': 0,
        'render': function (data, type, full, meta) {
          if (type === 'display') {
            data = '<div class="checkbox"><input type="checkbox" class="dt-checkboxes"><label></label></div>'; //body checkbox
          }
          return data;
        },
        'checkboxes': {
          'selectRow': true,
          'selectAllRender': '<div class="checkbox"><input type="checkbox" class="dt-checkboxes" ><label></label></div >'  //head checkbox
        }
      },
    { "orderable": false, "targets": [ 0, 1, ] }   
    ],
    dom: "<'row'<'col-sm-4'B><'col-sm-4 serach-center'f><'col-sm-4'>>" +
                  "<'row'<'col-sm-12'tr>>" +
                  "<'row content-wrapper pt-0'<'col-sm-4'i><'col-sm-6 text-right d-list-box'l><'col-sm-2 text-right'p>>",   
    'select': 'multi',
    'order': [[1, 'asc']]
  });
})(window, document, jQuery); 

/*=========================================================================================
	File Name: team.html
    
	BLOG PAGE CODE START
==========================================================================================*/

(function (window, document, $) {

  var $primary = '#5A8DEE';
  var $danger = '#FF5B5C';
  var $warning = '#FDAC41';
  var $info = '#00CFDD';
  var $gray_light = '#828D99';

  // table extended transactions
   $('#table-extended-blog').DataTable({
    "responsive":true,
    "searching": true,
    "lengthChange": true,
    "paging": true,
    "bInfo": true,
    "scrollX": false,
    "lengthMenu": [[ 25, 50, 100, 200, -1], [ 25, 50, 100, 200, "All"]],
    "columnDefs": [
    {
        'targets': 0,
        'render': function (data, type, full, meta) {
          if (type === 'display') {
            data = '<div class="checkbox"><input type="checkbox" class="dt-checkboxes"><label></label></div>'; //body checkbox
          }
          return data;
        },
        'checkboxes': {
          'selectRow': true,
          'selectAllRender': '<div class="checkbox"><input type="checkbox" class="dt-checkboxes" ><label></label></div >'  //head checkbox
        }
      },
    { "orderable": false, "targets": [ 0, 1, 2, 4, 5, 6, 7  ] }   
    ],
    dom: "<'row'<'col-sm-4'B><'col-sm-4 serach-center'f><'col-sm-4'>>" +
                  "<'row'<'col-sm-12'tr>>" +
                  "<'row content-wrapper pt-0'<'col-sm-4'i><'col-sm-6 text-right d-list-box'l><'col-sm-2 text-right'p>>",   
    'select': 'multi',
    'order': [[1, 'asc']]
  });
})(window, document, jQuery); 

/*=========================================================================================
	File Name: team.html
    
	IMAGE OVERLAY PAGE CODE START
==========================================================================================*/

(function (window, document, $) {

  var $primary = '#5A8DEE';
  var $danger = '#FF5B5C';
  var $warning = '#FDAC41';
  var $info = '#00CFDD';
  var $gray_light = '#828D99';

  // table extended transactions
   $('#table-extended-overlay').DataTable({
    "responsive":true,
    "searching": true,
    "lengthChange": true,
    "paging": true,
    "bInfo": true,
    "scrollX": false,
    "lengthMenu": [[ 25, 50, 100, 200, -1], [ 25, 50, 100, 200, "All"]],
    "columnDefs": [
    {
        'targets': 0,
      },
    { "orderable": false, "targets": [ 0, 1, 3, 5,  ] }   
    ],
    dom: "<'row'<'col-sm-4'B><'col-sm-4 serach-center'f><'col-sm-4'>>" +
                  "<'row'<'col-sm-12'tr>>" +
                  "<'row content-wrapper pt-0'<'col-sm-4'i><'col-sm-6 text-right d-list-box'l><'col-sm-2 text-right'p>>",   
    'select': 'multi',
    'order': [[1, 'asc']]
  });
})(window, document, jQuery); 

/*=========================================================================================
	File Name: team.html
    
	SYNDICATION PAGE CODE START
==========================================================================================*/

(function (window, document, $) {

  var $primary = '#5A8DEE';
  var $danger = '#FF5B5C';
  var $warning = '#FDAC41';
  var $info = '#00CFDD';
  var $gray_light = '#828D99';

  // table extended transactions
   $('#table-extended-syndication').DataTable({
    "responsive":true,
    "searching": true,
    "lengthChange": true,
    "paging": true,
    "bInfo": true,
    "scrollX": false,
    "lengthMenu": [[ 25, 50, 100, 200, -1], [ 25, 50, 100, 200, "All"]],
    "columnDefs": [
    {
        'targets': 0,
      },
    { "orderable": false, "targets": [ 0, 1, 5,  ] }   
    ],
    dom: "<'row'<'col-sm-4'B><'col-sm-4 serach-center'f><'col-sm-4'>>" +
                  "<'row'<'col-sm-12'tr>>" +
                  "<'row content-wrapper pt-0'<'col-sm-4'i><'col-sm-6 text-right d-list-box'l><'col-sm-2 text-right'p>>",   
    'select': 'multi',
    'order': [[1, 'asc']]
  });
})(window, document, jQuery); 

/*=========================================================================================
	File Name: team.html
    
    LOCATION PAGE CODE START
==========================================================================================*/

(function (window, document, $) {

  var $primary = '#5A8DEE';
  var $danger = '#FF5B5C';
  var $warning = '#FDAC41';
  var $info = '#00CFDD';
  var $gray_light = '#828D99';

  // table extended transactions
   $('#table-extended-locations').DataTable({
    "responsive":true,
    "searching": true,
    "lengthChange": true,
    "paging": true,
    "bInfo": true,
    "scrollX": false,
    "lengthMenu": [[ 25, 50, 100, 200, -1], [ 25, 50, 100, 200, "All"]],
    "columnDefs": [
    {
        'targets': 0,
      },
    { "orderable": false, "targets": [ 0, 6, ] }   
    ],
    dom: "<'row'<'col-sm-4'B><'col-sm-4 serach-center'f><'col-sm-4'>>" +
                  "<'row'<'col-sm-12'tr>>" +
                  "<'row content-wrapper pt-0'<'col-sm-4'i><'col-sm-6 text-right d-list-box'l><'col-sm-2 text-right'p>>",   
    'select': 'multi',
    'order': [[1, 'asc']]
  });
})(window, document, jQuery); 

/*=========================================================================================
	File Name:add-leads.html
    
    OTHER LEADS PAGE CODE START
==========================================================================================*/

(function (window, document, $) {

  var $primary = '#5A8DEE';
  var $danger = '#FF5B5C';
  var $warning = '#FDAC41';
  var $info = '#00CFDD';
  var $gray_light = '#828D99';
 
  // table extended transactions
   $('#table-extended-other-leads').DataTable({
    "responsive":true,
    "searching": true,
    "lengthChange": true,
    "paging": true,
    "bInfo": true,
    "scrollX": false,
    "lengthMenu": [[ 25, 50, 100, 200, -1], [ 25, 50, 100, 200, "All"]],
    "columnDefs": [
    {
        'targets': 0,
      },
    { "orderable": false, "targets": [ 0 ] }   
    ],
    dom: "<'row'<'col-sm-4'B><'col-sm-4 serach-center'f><'col-sm-4'>>" +
                  "<'row'<'col-sm-12'tr>>" +
                  "<'row content-wrapper pt-0'<'col-sm-4'i><'col-sm-6 text-right d-list-box'l><'col-sm-2 text-right'p>>",   
    'select': 'multi',
    'order': [[1, 'asc']]
  });
})(window, document, jQuery); 

/*=========================================================================================
	File Name: team.html
    
    GALLERY PAGE CODE START
==========================================================================================*/

(function (window, document, $) {

  var $primary = '#5A8DEE';
  var $danger = '#FF5B5C';
  var $warning = '#FDAC41';
  var $info = '#00CFDD';
  var $gray_light = '#828D99';

  // table extended transactions
   $('#table-extended-gallery').DataTable({
    "responsive":true,
    "searching": true,
    "lengthChange": true,
    "paging": true,
    "bInfo": true,
    "scrollX": false,
    "lengthMenu": [[ 25, 50, 100, 200, -1], [ 25, 50, 100, 200, "All"]],
    "columnDefs": [
    {
        'targets': 0,
        'render': function (data, type, full, meta) {
          if (type === 'display') {
            data = '<div class="checkbox"><input type="checkbox" class="dt-checkboxes"><label></label></div>'; //body checkbox
          }
          return data;
        },
        'checkboxes': {
          'selectRow': true,
          'selectAllRender': '<div class="checkbox"><input type="checkbox" class="dt-checkboxes" ><label></label></div >'  //head checkbox
        }
      },
    { "orderable": false, "targets": [ 0, 1, 2, ] }   
    ],
    dom: "<'row'<'col-sm-4'B><'col-sm-4 serach-center'f><'col-sm-4'>>" +
                  "<'row'<'col-sm-12'tr>>" +
                  "<'row content-wrapper pt-0'<'col-sm-4'i><'col-sm-6 text-right d-list-box'l><'col-sm-2 text-right'p>>",   
    'select': 'multi',
    'order': [[1, 'asc']]
  });
})(window, document, jQuery);


/*=========================================================================================
	File Name: newsletter-subscription.html
    
	NEWSLETTER SUBCRIPTION PAGE CODE START
==========================================================================================*/

(function (window, document, $) {

  var $primary = '#5A8DEE';
  var $danger = '#FF5B5C';
  var $warning = '#FDAC41';
  var $info = '#00CFDD';
  var $gray_light = '#828D99';

  // table extended transactions
   $('#table-extended-newletter').DataTable({
    "responsive":true,
    "searching": true,
    "lengthChange": true,
    "paging": true,
    "bInfo": true,
    "scrollX": false,
    "lengthMenu": [[ 25, 50, 100, 200, -1], [ 25, 50, 100, 200, "All"]],
    "columnDefs": [
    {
        'targets': 0,
        'render': function (data, type, full, meta) {
          if (type === 'display') {
            data = '<div class="checkbox"><input type="checkbox" class="dt-checkboxes"><label></label></div>'; //body checkbox
          }
          return data;
        },
        'checkboxes': {
          'selectRow': true,
          'selectAllRender': '<div class="checkbox"><input type="checkbox" class="dt-checkboxes" ><label></label></div >'  //head checkbox
        }
      },
    { "orderable": false, "targets": [ 0, 2, ] }   
    ],
    dom: "<'row'<'col-sm-4'B><'col-sm-4 serach-center'f><'col-sm-4'>>" +
                  "<'row'<'col-sm-12'tr>>" +
                  "<'row content-wrapper pt-0'<'col-sm-4'i><'col-sm-6 text-right d-list-box'l><'col-sm-2 text-right'p>>",   
    'select': 'multi',
    'order': [[1, 'asc']]
  });
})(window, document, jQuery);


/*=========================================================================================
	File Name: pages.html
    
	PAGES PAGE CODE START
==========================================================================================*/

(function (window, document, $) {

  var $primary = '#5A8DEE';
  var $danger = '#FF5B5C';
  var $warning = '#FDAC41';
  var $info = '#00CFDD';
  var $gray_light = '#828D99';

  // table extended transactions
   $('#table-extended-page').DataTable({ 
    "responsive":true,
    "searching": true,
    "lengthChange": true,
    "paging": true,
    "bInfo": true,
    "scrollX": false,
    "lengthMenu": [[ 25, 50, 100, 200, -1], [ 25, 50, 100, 200, "All"]],
    "columnDefs": [
    {
        'targets': 0,
        'render': function (data, type, full, meta) {
          if (type === 'display') {
            data = '<div class="checkbox"><input type="checkbox" class="dt-checkboxes"><label></label></div>'; //body checkbox
          }
          return data;
        },
        'checkboxes': {
          'selectRow': true,
          'selectAllRender': '<div class="checkbox"><input type="checkbox" class="dt-checkboxes" ><label></label></div >'  //head checkbox
        }
      },
    { "orderable": false, "targets": [ 0, 1, ] }   
    ],
    dom: "<'row'<'col-sm-4'B><'col-sm-4 serach-center'f><'col-sm-4'>>" +
                  "<'row'<'col-sm-12'tr>>" +
                  "<'row content-wrapper pt-0'<'col-sm-4'i><'col-sm-6 text-right d-list-box'l><'col-sm-2 text-right'p>>",   
    'select': 'multi',
    'order': [[1, 'asc']]
  });
})(window, document, jQuery);


$(document).ready(function(){ 
// Monday Time change
     $('#monday-felid-content').change(function(){
        $('.monday-time-block').hide();
        $('#' + $(this).val()).show();
      });
    // Tuesday Time change
     $('#tuesday-felid-content').change(function(){
        $('.tuesday-time-block').hide();
        $('#' + $(this).val()).show();
      });
    // Wednesday Time change
     $('#wednesday-felid-content').change(function(){
        $('.wednesday-time-block').hide();
        $('#' + $(this).val()).show();
      });
    // Thursday Time change
     $('#thursday-felid-content').change(function(){
        $('.thursday-time-block').hide();
        $('#' + $(this).val()).show();
      });
    // Friday Time change
     $('#friday-felid-content').change(function(){
        $('.friday-time-block').hide();
        $('#' + $(this).val()).show();
      });
    // Saturday Time change
     $('#saturday-felid-content').change(function(){
        $('.saturday-time-block').hide();
        $('#' + $(this).val()).show();
      });
    // Sunday Time change
     $('#sunday-felid-content').change(function(){
        $('.sunday-time-block').hide();
        $('#' + $(this).val()).show();
      });
    
    // color code start code
    
    var colorList = [ '000000', '993300', '333300', '003300', '003366', '000066', '333399', '333333', 
'660000', 'FF6633', '666633', '336633', '336666', '0066FF', '666699', '666666', 'CC3333', 'FF9933', '99CC33', '669966', '66CCCC', '3366FF', '663366', '999999', 'CC66FF', 'FFCC33', 'FFFF66', '99FF66', '99CCCC', '66CCFF', '993366', 'CCCCCC', 'FF99CC', 'FFCC99', 'FFFF99', 'CCffCC', 'CCFFff', '99CCFF', 'CC99FF', 'FFFFFF' ];
		var picker = $('#color-picker');

		for (var i = 0; i < colorList.length; i++ ) {
			picker.append('<li class="color-item" data-hex="' + '#' + colorList[i] + '" style="background-color:' + '#' + colorList[i] + ';"></li>');
		}

		$('body').click(function () {
			picker.fadeOut();
		});

		$('.call-picker').click(function(event) {
			event.stopPropagation();
			picker.fadeIn();
			picker.children('li').hover(function() {
				var codeHex = $(this).data('hex');

				$('.color-holder').css('background-color', codeHex);
				$('#pickcolor').attr(codeHex);
			});
		});
});

//URL CLICK FUNCTION CODE

jQuery(document).ready(function($) {
    $(".clickable-row").click(function() {
        window.location = $(this).data("href");
    });
});


/*=========================================================================================
    File Name: Footer Customization.html
    Footer Customization
==========================================================================================*/

$(document).ready(function(){ 
 // Financing Special
    
    $("#footerSwitch11").click(function(){
        $('.footer-hidden-fin1').toggleClass("footer-show-fin1");
    });
    
    // Financing Special
    
    $("#footerSwitch12").click(function(){
        $('.footer-hidden-fin2').toggleClass("footer-show-fin2");
    });
    
    // Financing Special
    
    $("#footerSwitch13").click(function(){
        $('.footer-hidden-fin3').toggleClass("footer-show-fin3");
    });
    
    // Financing Special
    
    $("#footerSwitch14").click(function(){
        $('.footer-hidden-fin4').toggleClass("footer-show-fin4");
    });
    
//    $(".add-page-design-box").change(function(){
//        $('.show-next-tab').addClass("active");
//    });
    
    // GALLERY PAGE START
    
     $("#checkboxgallery").click(function () {
        $('.gallery-select  li input:checkbox').not(this).prop('checked', this.checked);
     });
    
    // SELECT ALL START
    
    $('#checkboxgallery').change(function(){
        
    if($(this).is(":checked")) {
       $('.gallery-box-add').addClass('gallery-select-box');
    } else  {
        $('.gallery-box-add').removeClass('gallery-select-box');
    }
});
    
    // SINGLE SELECT START
    
    $('.gallery-select  li input').click(function(){
       
       var totalCheckboxes =$('.gallery-select  li input:checked').length;

	if($(this).is(":checked")) {
        $('.gallery-box-add').addClass('gallery-select-box');
    } else {
		if(totalCheckboxes==0){
			$('.gallery-box-add').removeClass('gallery-select-box');
		}
       
    }
});
     // ALL CLEAR START
    
    $('.close-btn').click(function(){
    $('.gallery-box-add').removeClass('gallery-select-box');
    $('.gallery-select  li input, #checkboxgallery').prop('checked',false);    
});
});

/*=========================================================================================
    File Name: Site menu .html
    Site menu
==========================================================================================*/

$(document).ready(function()
    {

        var updateOutput = function(e)
        {
            var list   = e.length ? e : $(e.target),
                output = list.data('output');
            if (window.JSON) {
                output.val(window.JSON.stringify(list.nestable('serialize')));//, null, 2));
            } else {
                output.val('JSON browser support required for this demo.');
            }
        };

        $('#domenu').domenu({
            data: '[{"id":11,"title":"Home","http":""},{"id":10,"title":"Blog","http":""},{"id":9,"title":"Gallery","http":""},{"id":6,"title":"About Us","http":"","children":[{"id":5,"title":"Service","http":""},{"id":4,"title":"Contact us","http":"","children":[{"id":3,"title":"Item 3","http":""},{"id":2,"title":"Item 2","http":"","children":[{"id":7,"title":"Example","http":"http://google.com"},{"id":8,"title":"Item 8","http":""}]}]}]},{"id":1,"title":"Item 1","http":""}]'
        }).parseJson();
    });

/*=========================================================================================
	File Name: menu-list.html
    
	MENU LIST PAGE CODE START
==========================================================================================*/

(function (window, document, $) {

  var $primary = '#5A8DEE';
  var $danger = '#FF5B5C';
  var $warning = '#FDAC41';
  var $info = '#00CFDD';
  var $gray_light = '#828D99';

  // table extended transactions
   $('#table-extended-menu').DataTable({
    "responsive":true,
    "searching": true,
    "lengthChange": true,
    "paging": true,
    "bInfo": true,
    "scrollX": false,
    "lengthMenu": [[ 25, 50, 100, 200, -1], [ 25, 50, 100, 200, "All"]],
    "columnDefs": [
    {
        'targets': 0,
      },
    { "orderable": false, "targets": [ 0, 4 ] }   
    ],
    dom: "<'row'<'col-sm-4'B><'col-sm-4 serach-center'f><'col-sm-4'>>" +
                  "<'row'<'col-sm-12'tr>>" +
                  "<'row content-wrapper pt-0'<'col-sm-4'i><'col-sm-6 text-right d-list-box'l><'col-sm-2 text-right'p>>",   
    'select': 'multi',
    'order': [[1, 'asc']]
  });
})(window, document, jQuery);


/*=========================================================================================
	File Name: slider.html
    
	SLIDER PAGE CODE START
==========================================================================================*/

(function (window, document, $) {

  var $primary = '#5A8DEE';
  var $danger = '#FF5B5C';
  var $warning = '#FDAC41';
  var $info = '#00CFDD';
  var $gray_light = '#828D99';

  // table extended transactions
   $('#table-extended-slider').DataTable({
    "responsive":true,
    "searching": true,
    "lengthChange": true,
    "paging": true,
    "bInfo": true,
    "scrollX": false,
    "lengthMenu": [[ 25, 50, 100, 200, -1], [ 25, 50, 100, 200, "All"]],
    "columnDefs": [
    {
        'targets': 0,
        'render': function (data, type, full, meta) {
          if (type === 'display') {
            data = '<div class="checkbox"><input type="checkbox" class="dt-checkboxes"><label></label></div>'; //body checkbox
          }
          return data;
        },
        'checkboxes': {
          'selectRow': true,
          'selectAllRender': '<div class="checkbox"><input type="checkbox" class="dt-checkboxes" ><label></label></div >'  //head checkbox
        }
      },
    { "orderable": false, "targets": [ 0, 1, 2, ] }   
    ],
    dom: "<'row'<'col-sm-4'B><'col-sm-4 serach-center'f><'col-sm-4'>>" +
                  "<'row'<'col-sm-12'tr>>" +
                  "<'row content-wrapper pt-0'<'col-sm-4'i><'col-sm-6 text-right d-list-box'l><'col-sm-2 text-right'p>>",   
    'select': 'multi',
    'order': [[1, 'asc']]
  });
})(window, document, jQuery);

/*=========================================================================================
    File Name: Site menu .html
    Site menu
==========================================================================================*/
document.body.insertAdjacentHTML('afterbegin', '<div id="clpi-m"><div id="clpi-p"><div id="clpi-w"><div id="clpi-a"><div id="clpi-o"></div></div><div class="clpi-r"><div class="clpi-e clpi-e-hsl"> <input type="range" id="clpi-i-hsl" min="0" max="1" value="1" step="0.01"><div class="clpi-c" id="clpi-c-hsl"></div></div><div class="clpi-e" id="clpi-e-alpha"> <input type="range" id="clpi-i-alpha" min="0" max="1" value="1" step="0.01"><div class="clpi-c" id="clpi-c-alpha"></div></div></div></div><div class="clpi-b"><div class="clpi-b-hex"><input type="text" id="clpi-c"></div><div class="clpi-u"></div></div><div class="clpi-x"><div class="clpi-z"><div class="clpi-v"><div class="clpi-o" style="background: rgb(0, 0, 0);"></div><div class="clpi-o" style="background: rgb(255, 255, 255);"></div></div><div id="clpi-l"></div></div><div class="clpi-l-angle"> <input type="number" id="clpi-l-angle" placeholder="0"></div></div></div></div>');

var colorPicker = {
    input: null,
    thumb: null,
    value: '',
    h: 0,
    s: 0,
    l: 0,
    alpha: 1,

    gradientActive: false,
    gradientControl: null,
    gradientAngle: 0,
    gradientColor: document.getElementById('clpi-l'),
    gradientAngleInut: document.getElementById('clpi-l-angle'),

    mask: document.getElementById('clpi-m'),
    picker: document.getElementById('clpi-p'),

    currentColor: document.getElementById('clpi-c'),

    area: document.getElementById('clpi-a'),
    areaPosition: 0,

    pointer: document.getElementById('clpi-o'),
    pointerMove: false,

    hslInput: document.getElementById('clpi-i-hsl'),
    hslRangeControl: document.getElementById('clpi-c-hsl'),
    alphaRange: document.getElementById('clpi-e-alpha'),
    alphaInput: document.getElementById('clpi-i-alpha'),
    alphaRangeControl: document.getElementById('clpi-c-alpha'),

    cssClassControlActive: 'clpi-o-active',

    init: function (node) {
        let parent = node.closest('.clpi-wrapper'),
            input = parent.querySelector('input'),
            thumb = parent.querySelector('div');

        this.input = input;
        this.thumb = thumb;

        if (input.matches('.clpi-gradient')) {
            this.picker.classList.add('clpi-l-support');
        }

        this.mask.classList.add('clpi-m-show');

        let pos = input.getBoundingClientRect(),
            height = this.picker.getBoundingClientRect().height;

        let setToBottom = pos.top + pos.height + height > window.innerHeight;

        if (setToBottom) {
            setToBottom = pos.top - height - 5;
        } else {
            setToBottom = pos.top + pos.height + 5;
        }

        this.picker.style.top = setToBottom + 'px';
        this.picker.style.left = pos.left + 'px';

        let val = input.value;

        if (val.length < 3) return;

        if (val.includes('gradient')) {
            this.initGradient(val);
        } else if (val.includes('rgb')) {
            this.initRgb(val);
        } else {
            this.initHex(val);
        }
    },

    initGradient: function (val) {
        this.gradientActive = true;
        this.picker.classList.add('clpi-l-active');

        val = val.replace('linear-gradient(', '').slice(0, -1);

        let angle = val.split(',');
        angle = angle[0].replace(/[^\d,]/g, '');

        if (angle) {
            this.gradientAngleInut.value = parseInt(angle);
            this.gradientAngle = parseInt(angle);
        }

        let controls = val.match(/\((.*?)\)/g);

        // in case custom gradient input
        if (!controls) return;

        controls.forEach(function (v, k) {

            let rgb = v.replace(/[^\d,]/g, '').split(',').map(function (v) {
                return parseFloat(v)
            });

            if (rgb.length > 3) {
                rgb[3] = rgb[3] / 100;
            }

            document.querySelectorAll('.clpi-o')[k].style.background = rgb.length > 3 ? 'rgba(' + rgb.join(',') + ')' : 'rgb(' + rgb.join(',') + ')';

            controls[k] = rgb;
        });

        this.setGradientControl(document.querySelector('.clpi-o'));
    },

    initRgb: function (val) {
        let rgb = val.replace(/[^\d,]/g, '').split(',').map(function (v) {
            return parseFloat(v)
        });

        if (rgb.length > 3) {
            rgb[3] = rgb[3] / 100;
        }

        this.update(rgb);
    },

    initHex: function (val) {
        this.setCurrent(val);
    },

    change: function () {
        let n = this.hslRgb(this.h, this.s, this.l),
            hex = this.rgbHex(n);

        this.currentColor.value = hex;
        this.area.style.background = 'hsl(' + parseInt(this.h * 360) + ', 100%, 50%)';
        this.alphaRange.style.background = 'linear-gradient(to right, rgba(' + n.join(',') + ', 0) 0%, rgb(' + n.join(',') + ') 100%)';

        if (this.alpha == 1) {
            this.value = hex;
        } else {
            this.value = 'rgba(' + n.join(',') + ',' + this.alpha + ' )';
        }

        if (this.gradientActive) {
            this.gradientControl.style.background = this.value;

            let grColors = [];

            for (var item of document.querySelectorAll('.clpi-o')) {
                let bg = item.style.background;
                bg = bg ? bg : '#000';

                // firefox fix
                bg = bg.replace(' none repeat scroll 0% 0%', '');

                grColors.push(bg);
            }

            this.gradientColor.style.background = 'linear-gradient(to right, ' + grColors[0] + ' 0%, ' + grColors[1] + ' 100%)';

            let angle = this.gradientAngle ? this.gradientAngle + 'deg' : 'to bottom';

            this.value = 'linear-gradient(' + angle + ', ' + grColors[0] + ' 0%, ' + grColors[1] + ' 100%)';

        }

        this.thumb.style.background = this.value;

        this.input.value = this.value;
      
        let that = this;
      
        window.dispatchEvent(new CustomEvent('colorPickerTick', {
            detail: {
                el: that.input,
            }
        }));
    },

    triggerChange: function() {
        let that = this;

        window.dispatchEvent(new CustomEvent('colorPickerChange', {
            detail: {
                el: that.input,
            }
        }));
    },

    update: function (rgb) {
        let hsl = this.rgbHsl(rgb[0], rgb[1], rgb[2]);

        this.hslInput.value = hsl[0];

        this.setHslRange(hsl[0]);

        if (rgb.length > 3) {
            this.alphaInput.value = rgb[3];
            this.alpha = rgb[3];
            this.setAlphaRange(rgb[3]);
        } else {
            this.alphaInput.value = 1;
            this.alpha = 1;
            this.setAlphaRange(1);
        }

        this.h = hsl[0];
        this.s = hsl[1];
        this.l = hsl[2];

        hsl[2] = hsl[2] * 200 - 200;
        hsl[2] = hsl[2] < 0 ? hsl[2] * -1 : hsl[2];

        this.pointer.style.top = hsl[2] + 'px';
        this.pointer.style.left = hsl[1] * 200 + 'px';

        this.change();
    },

    close: function () {
        for (var item of document.querySelectorAll('.clpi-o')) {
            item.classList.remove(this.cssClassControlActive);
        }

        this.mask.classList.remove('clpi-m-show');
        this.picker.classList.remove('clpi-l-active');
        this.picker.classList.remove('clpi-l-support');

        this.reset();
    },

    reset: function () {
        this.h = 0;
        this.s = 0;
        this.l = 0;
        this.alpha = 1;
        this.value = '';

        this.gradientActive = false;
        this.gradientAngle = 0;

        this.pointer.removeAttribute('style');
        this.alphaRangeControl.removeAttribute('style');
        this.hslRangeControl.removeAttribute('style');
        this.currentColor.value = '';
        this.gradientAngleInut.value = '';
        this.hslInput.value = 1;
        this.alphaInput.value = 1;
    },

    setHsl: function (v) {
        this.h = v;
        this.setHslRange(v);
        this.change();
    },

    setHslRange: function (v) {
        this.hslRangeControl.style.left = v * 100 + '%';
    },

    setAlpha: function (v) {
        this.alpha = v;
        this.setAlphaRange(v);
        this.change();
    },

    setAlphaRange: function (v) {
        this.alphaRangeControl.style.left = v * 100 + '%';
    },

    setCurrent: function (hex) {
        if (hex.length < 6) return;

        let rgb = this.hexRgb(hex);

        if (!rgb) return;

        this.update(rgb);

        this.triggerChange();
    },

    setGradientControl: function (e) {
        this.gradientControl = e;

        for (var item of document.querySelectorAll('.clpi-o')) {
            item.classList.remove(this.cssClassControlActive);
        }

        this.gradientControl.classList.add(this.cssClassControlActive);


        let bg = this.gradientControl.style.background;

        if (bg) {
            // firefox fix
            bg = bg.replace(' none repeat scroll 0% 0%', '');
            let rgb = bg.replace(/[^\d,]/g, '').split(',').map(function (v) {
                return parseFloat(v)
            });

            if (typeof rgb[3] !== 'undefined') {
                rgb[3] = rgb[3] / 100;
            }

            this.update(rgb);
        }
    },

    setGradientAngle: function (v) {
        this.gradientAngle = v;
        this.change();
        this.triggerChange();
    },

    gradientShowToggle: function () {
        for (var item of document.querySelectorAll('.clpi-o')) {
            item.classList.remove(this.cssClassControlActive);
        }

        this.picker.classList.toggle('clpi-l-active');
        this.gradientActive = !this.gradientActive;

        if (this.gradientActive) {
            this.gradientControl = document.querySelector('.clpi-o');
            this.gradientControl.classList.add(this.cssClassControlActive);
        }

        this.change();
        this.triggerChange();
    },

    startDrag: function (e) {
        this.pointerMove = true;
        this.areaPosition = this.area.getBoundingClientRect();
        this.drag(e);
    },

    drag: function (e) {
        if (this.pointerMove) {
            let y = e.clientY - this.areaPosition.top,
                x = e.clientX - this.areaPosition.left;

            y = y < 0 ? 0 : y;
            y = y > 200 ? 200 : y;

            x = x < 0 ? 0 : x;
            x = x > 200 ? 200 : x;

            this.pointer.style.top = y + 'px';
            this.pointer.style.left = x + 'px';

            x = x / 200;
            y = y / 200 - 1;

            x = -x > 0 ? -x : x;
            y = -y > 0 ? -y : y;

            this.s = parseFloat(x.toFixed(2));
            this.l = parseFloat(y.toFixed(2));

            this.change();
        }
    },

    stopDrag: function (e) {
        if (this.pointerMove === true) {
            this.triggerChange();
        }
        this.pointerMove = false;
    },

    hslRgb: function (h, s, v) {
        var r, g, b, i, f, p, q, t;

        i = Math.floor(h * 6);
        f = h * 6 - i;
        p = v * (1 - s);
        q = v * (1 - f * s);
        t = v * (1 - (1 - f) * s);

        switch (i % 6) {
            case 0:
                r = v, g = t, b = p;
                break;
            case 1:
                r = q, g = v, b = p;
                break;
            case 2:
                r = p, g = v, b = t;
                break;
            case 3:
                r = p, g = q, b = v;
                break;
            case 4:
                r = t, g = p, b = v;
                break;
            case 5:
                r = v, g = p, b = q;
                break;
        }

        return [
            Math.round(r * 255),
            Math.round(g * 255),
            Math.round(b * 255)
        ];
    },

    rgbHsl: function (red, green, blue) {
        var rr, gg, bb,
            r = arguments[0] / 255,
            g = arguments[1] / 255,
            b = arguments[2] / 255,
            h, s,
            v = Math.max(r, g, b),
            diff = v - Math.min(r, g, b),
            diffc = function (c) {
                return (v - c) / 6 / diff + 1 / 2;
            };

        if (diff == 0) {
            h = s = 0;
        } else {
            s = diff / v;
            rr = diffc(r);
            gg = diffc(g);
            bb = diffc(b);

            if (r === v) {
                h = bb - gg;
            } else if (g === v) {
                h = (1 / 3) + rr - bb;
            } else if (b === v) {
                h = (2 / 3) + gg - rr;
            }
            if (h < 0) {
                h += 1;
            } else if (h > 1) {
                h -= 1;
            }
        }

        return [parseFloat(h.toFixed(3)), parseFloat(s.toFixed(3)), parseFloat(v.toFixed(3))]
    },

    rgbHex: function (rgb) {
        var hex = [rgb[0].toString(16), rgb[1].toString(16), rgb[2].toString(16)];

        hex.forEach(function (v, k) {
            if (v.length === 1) {
                hex[k] = '0' + v;
            }
        });

        return '#' + hex.join('');
    },

    hexRgb: function (hex) {
        let shorthandRegex = /^#?([a-f\d])([a-f\d])([a-f\d])$/i;
        hex = hex.replace(shorthandRegex, function (m, r, g, b) {
            return r + r + g + g + b + b;
        });

        let result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);

        return result ? [parseInt(result[1], 16), parseInt(result[2], 16), parseInt(result[3], 16)] : null;
    },


    wrap: function (item) {
        let wrapper = document.createElement('div'),
            thumb = document.createElement('div'),
            value = item.value;
        wrapper.className = 'clpi-wrapper';
        thumb.className = 'clpi-thumb';

        if (value.length) {
            thumb.style.background = value;
        }

        item.parentNode.appendChild(wrapper);

        wrapper.appendChild(item);

        return wrapper.appendChild(thumb);
    },

    initAll: function () {
        for (var item of document.querySelectorAll('.clpi:not(.clpi-initialized)')) {
            item.classList.add('clpi-initialized');

            this.wrap(item);
        }
    },

    setup: function () {
        let that = this;

        this.hslInput.addEventListener('input', function (e) {
            that.setHsl(e.target.value);
        });

        this.hslInput.addEventListener('change', function (e) {
            that.triggerChange();
        });

        this.alphaRange.addEventListener('input', function (e) {
            that.setAlpha(e.target.value);
        });

        this.alphaRange.addEventListener('change', function (e) {
            that.triggerChange();
        });

        this.currentColor.addEventListener('input', function (e) {
            that.setCurrent(e.target.value);
        });

        this.gradientAngleInut.addEventListener('input', function (e) {
            that.setGradientAngle(e.target.value);
        });

        this.area.addEventListener('mousedown', function (e) {
            that.startDrag(e);
        });

        this.mask.addEventListener('mousedown', function (e) {
            if (e.target.matches('#clpi-m')) {
                that.close();
            }
        });

        document.addEventListener('mousemove', function (e) {
            that.drag(e);
        });

        document.addEventListener('mouseup', function (e) {
            that.stopDrag(e);
        });

        document.addEventListener('click', function (e) {
            if (e.target.matches('.clpi') || e.target.matches('.clpi-thumb')) {
                colorPicker.init(e.target);
            }

            if (e.target.matches('.clpi-o')) {
                colorPicker.setGradientControl(e.target);
            }

            if (e.target.matches('.clpi-u')) {
                colorPicker.gradientShowToggle();
            }
        });

        this.initAll();
    },

};

colorPicker.setup();



/*=========================================================================================
    File Name: form-select2.js
    multiple select file 
==========================================================================================*/
(function(window, document, $) {
	'use strict';

  // Basic Select2 select
	$(".select2").select2({
    // the following code is used to disable x-scrollbar when click in select input and
    // take 100% width in responsive also
    dropdownAutoWidth: true,
    width: '100%'
  });

  // Select With Icon
  $(".select2-icons").select2({
      dropdownAutoWidth: true,
      width: '100%',
      minimumResultsForSearch: Infinity,
      templateResult: iconFormat,
      templateSelection: iconFormat,
      escapeMarkup: function(es) { return es; }
  });

  // Format icon
  function iconFormat(icon) {
      var originalOption = icon.element;
      if (!icon.id) { return icon.text; }
      var $icon = "<i class='" + $(icon.element).data('icon') + "'></i>" + icon.text;

      return $icon;
  }
    
$(".js-example-tokenizer").select2({
    tags: true,
    tokenSeparators: [',', ' ']
})    

})(window, document, jQuery);

 
/*=========================================================================================
	File Name: editor-quill.js
	Description: Quill is a modern rich text editor built for compatibility and extensibility.
==========================================================================================*/
(function (window, document, $) {
  'use strict';

  var Font = Quill.import('formats/font');
  Font.whitelist = ['sofia', 'slabo', 'roboto', 'inconsolata', 'ubuntu'];
  Quill.register(Font, true);

  // Bubble Editor

  var bubbleEditor = new Quill('#bubble-container .editor', {
    bounds: '#bubble-container .editor',
    modules: {
      'formula': true,
      'syntax': true
    },
    theme: 'bubble'
  });

  // Snow Editor

  var snowEditor = new Quill('#snow-container .editor', {
    bounds: '#snow-container .editor',
    modules: {
      'formula': true,
      'syntax': true,
      'toolbar': '#snow-container .quill-toolbar'
    },
    theme: 'snow'
  });

  // Full Editor

  var fullEditor = new Quill('#full-container .editor', {
    bounds: '#full-container .editor',
    modules: {
      'formula': true,
      'syntax': true,
      'toolbar': [
        [{
          'font': []
        }, {
          'size': []
        }],
        ['bold', 'italic', 'underline', 'strike'],
        [{
          'color': []
        }, {
          'background': []
        }],
        [{
          'script': 'super'
        }, {
          'script': 'sub'
        }],
        [{
          'header': '1'
        }, {
          'header': '2'
        }, 'blockquote', 'code-block'],
        [{
          'list': 'ordered'
        }, {
          'list': 'bullet'
        }, {
          'indent': '-1'
        }, {
          'indent': '+1'
        }],
        ['direction', {
          'align': []
        }],
        ['link', 'image', 'video', 'formula'],
        ['clean']
      ],
    },
    theme: 'snow'
  });

  var editors = [bubbleEditor, snowEditor, fullEditor];

})(window, document, jQuery);






    
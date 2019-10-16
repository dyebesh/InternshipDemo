/*Modal pop-up for conformation */

define(['jquery', 'core/modal_factory', 'core/modal_events', 'core/templates'],
        function($, ModalFactory, ModalEvents, Templates) {
  return {
    init :function(weight,activity,coursetotal) {
      var content;
      if(activity == undefined && weight == undefined && coursetotal == undefined){
      content =" There is no validation error in gradebook.";
      } else if(activity == undefined||coursetotal == undefined){
        content="<ul><li>"+weight+"</li></ul>";
      }else if(weight == undefined || coursetotal == undefined){
        content="<ul><li>"+activity+"</li></ul>";
      }else if(weight == undefined || activity == undefined){
        content="<ul><li>"+coursetotal+"</li></ul>";  
      }else{
        content="<ul><li>"+activity+"</li><li>"+weight+"</li><li>"+coursetotal+"</li></ul>";
      }
        var trigger = $(".validate #validate_form");
        ModalFactory.create({
          type: ModalFactory.types.CONFIRM,
          title: 'Validation Error',
          body: content
        }, trigger)
        .done(function(modal) {
          modal.getRoot().on(ModalEvents.save, function(e) {
            // Stop the default save button behaviour which is to close the modal.
            e.preventDefault();
            // Do your form validation here.
          });
        });
      }
 }    
});
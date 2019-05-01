    $( "#yearList" ).change(function() {
      var selectedYear = $( "#yearList" ).val();
      var currentYear = new Date().getFullYear();
      var option = "";
      
      if(selectedYear < currentYear){
        option += getOption(12);
        
      }else{
        
        var month = new Date().getMonth()+1;
        
         option += getOption(month);
      }
      $('#monthList').html(option);
    });
          
          
  function getOption(monthNo){
    var option = "";
    var i,j;
    
    var monthsArray = [];
        monthsArray[0] = 'January';
        monthsArray[1] = 'February';
        monthsArray[2] = 'March';
        monthsArray[3] = 'April';
        monthsArray[4] = 'May';
        monthsArray[5] = 'June';
        monthsArray[6] = 'July';
        monthsArray[7] = 'August';
        monthsArray[8] = 'September';
        monthsArray[9] = 'October';
        monthsArray[10] = 'November';
        monthsArray[11] = 'December';
    
    for(i = 0; i < monthNo; i++){
      j=i+1;
      option += "<option value='"+j+"'>"+monthsArray[i]+"</option>";
    }
    
    return option;
  }
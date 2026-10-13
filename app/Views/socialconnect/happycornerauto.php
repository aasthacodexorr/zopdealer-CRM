<?php  
      // Static template: START
      echo $template = <<<HEREDOC
        {$invLine['year']} {$invLine['make']} {$invLine['model']} {$invLine['stock_no']}         
         
        Year:  {$invLine['year']}  
        Make:  {$invLine['make']}  
        Model: {$invLine['model']} 
        Odometer: {$invLine['odometer']} {$invLine['km_miles']} 
        
        {$invLine['vehicle_description']} 
         
        Link:  {$invLine['vdpurl']}
        HEREDOC;
      // Static template: END  

?>
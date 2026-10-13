<?php  
      // Static template: START
      echo $template = <<<HEREDOC
        {$invLine['year']} {$invLine['make']} {$invLine['model']}          
         
        Year:  {$invLine['year']}  
        Make:  {$invLine['make']}  
        Model: {$invLine['model']} 
        Odometer: {$invLine['odometer']} {$invLine['km_miles']}
        Stock: {$invLine['stock_no']} 
         
         
        Link:  {$invLine['vdpurl']}
        HEREDOC;
      // Static template: END  

?>
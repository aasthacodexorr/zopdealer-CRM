<?php  
      // Static template: START
      echo $template = <<<HEREDOC
        {$invLine['year']} {$invLine['make']} {$invLine['model']} {$invLine['trim']}         
         
        Year:  {$invLine['year']}  
        Make:  {$invLine['make']}  
        Model: {$invLine['model']} 
        Odometer: {$invLine['odometer']} {$invLine['km_miles']} 
        Stock: {$invLine['stock_no']}
        Price: {$invLine['price']}  
         
        Link:  {$invLine['vdpurl']}
        
        AMVIC licensed business
        HEREDOC;
      // Static template: END  

?>
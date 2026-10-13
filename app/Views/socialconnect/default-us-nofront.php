<?php  
      // Static template: START
      echo $template = <<<HEREDOC
        {$invLine['year']} {$invLine['make']} {$invLine['model']} {$invLine['trim']}         
         
        Year:  {$invLine['year']}  
        Make:  {$invLine['make']}  
        Model: {$invLine['model']} 
        Odometer: {$invLine['odometer']} {$invLine['km_miles']} 
        Price: {$invLine['price']}  
         
        Link:  {$invLine['siteurl']}
        HEREDOC;
      // Static template: END  

?>
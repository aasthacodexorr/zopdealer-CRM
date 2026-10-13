<?php

namespace App\Libraries\DealerTrack\Import\Lender;

class ScotiaDealerAdvantage extends DefaultLender
{
    protected array $taxable_fees = ['dealer_doc_fee'];

    protected array $non_taxable_fees = ['DealerAdministrationFee', 'administration_fee'];

    protected array $taxable_warranties = [
        'ExtendedWarranty',
        'extended_warranty',
    ];

    protected array $non_taxable_warranties = [];

    protected array $taxable_insurances = [
        'LifeInsurance',
        'AHInsurance',
        'life_insurance',
        'ah_insurance'
    ];

    protected array $warranties_as_fees = ['ReplacementWarranty', 'replacement_warranty'];

    //Licence Fee is missing, DOC Fee is Missing and Dealer Administration Fee is most likely wrong.

    //Update : Only Doc Fee might be missing. Admin Fee includes PPSA Fee but not Lien Registration Fee.

    protected bool $has_other_aftermarket_fee = false;
}

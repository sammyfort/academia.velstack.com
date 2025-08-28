<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class GPSRule implements ValidationRule
{
    protected array $validAreaCodes = [
        'A2', 'AY', 'AN', 'AI', 'A9', 'AK', 'AO', 'AQ', 'A3', 'AV', 'AA', 'AH', 'AT', 'AU', 'A6', 'AR', 'AF', 'AW',
        'AS', 'AB', 'AE', 'AD', 'A7', 'AZ', 'AX', 'AC', 'AG', 'A4', 'AJ', 'AM', 'AP', 'Bu', 'BA', 'BE', 'BK', 'BP', 'BY',
        'BT', 'BV', 'BC', 'BF', 'BL', 'BG', 'BZ', 'BX', 'BQ', 'BB', 'BJ', 'BN', 'BH', 'B2', 'BW', 'BR', 'BD', 'BI',
        'BO', 'BS', 'B3', 'CA', 'CB', 'CX', 'CG', 'CT', 'CP', 'CR', 'CC', 'CH', 'CO', 'CS', 'CE', 'CK', 'CU', 'CJ',
        'CW', 'CF', 'CM', 'CV', 'E2', 'ET', 'EZ', 'EK', 'EI', 'EG', 'EW', 'E3', 'EO', 'ED', 'EP', 'EJ', 'ES', 'EM',
        'EB', 'EE', 'EQ', 'EL', 'EU', 'EA', 'EX', 'EF', 'EH', 'EN', 'EV', 'EY', 'Ga', 'GB', 'GW', 'GZ', 'GY', 'GC',
        'GK', 'GN', 'GX', 'GE', 'GL', 'GO', 'GD', 'GS', 'GM', 'GT', 'NB', 'N4', 'NA', 'NN', 'NS', 'NF', 'NY', 'NP',
        'NE', 'NK', 'NO', 'NU', 'NL', 'N3', 'NG', 'NM', 'N2', 'NW', 'N5', 'NC', 'NR', 'NI', 'NX', 'NT', 'ND', 'NZ',
        'UA', 'UO', 'UK', 'UT', 'UW', 'UR', 'UL', 'UU', 'US', 'UN', 'UB', 'UG', 'UP', 'XD', 'XO', 'XW', 'XJ', 'XN',
        'XX', 'XK', 'XS', 'XY', 'XL', 'XT', 'VA', 'VX', 'VI', 'VK', 'VR', 'VO', 'VE', 'VF', 'VB', 'VC', 'VY', 'VQ',
        'VD', 'VG', 'VV', 'VJ', 'VZ', 'VS', 'VT', 'VW', 'VH', 'VM', 'VP', 'VN', 'WH', 'WB', 'WQ', 'WF', 'WU', 'WY',
        'WA', 'WO', 'WM', 'WG', 'WT', 'WZ', 'WC', 'WE', 'WN', 'WS', 'WW', 'WD', 'WJ', 'WP', 'WR', 'WX',
    ];

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!preg_match('/^([A-Z]{2,3})-[A-Za-z0-9]{3,4}-\d{4}$/', $value, $matches)) {
            $fail('The :attribute must be in the format AREA-123-4567 or AREA-AB12-5678.');
            return;
        }

        $areaCode = $matches[1];
        if (!in_array(strtoupper($areaCode), $this->validAreaCodes)) {
            $fail('The :attribute has an invalid area code.');
        }
    }
}

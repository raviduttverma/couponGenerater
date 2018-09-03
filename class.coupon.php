<?php
class coupon {
    CONST MIN_LENGTH = 8;
    
    /**
     * MASK FORMAT [XXX-XXX]
     * 'X' this is random symbols
     * '-' this is separator
     *
     * @param array $options
     * @return string
     * @throws Exception
     */
    static public function generate($options = []) {

        $length         = (isset($options['length']) ? filter_var($options['length'], FILTER_VALIDATE_INT, ['options' => ['default' => self::MIN_LENGTH, 'min_range' => 1]]) : self::MIN_LENGTH );
        $mask           = (isset($options['mask']) ? filter_var($options['mask'], FILTER_SANITIZE_STRING) : false );
        $uppercase    = ['Q', 'W', 'E', 'R', 'T', 'Y', 'U', 'I', 'O', 'P', 'A', 'S', 'D', 'F', 'G', 'H', 'J', 'K', 'L', 'Z', 'X', 'C', 'V', 'B', 'N', 'M'];
        $characters   = [];
        $coupon = '';
        $characters = array_merge($characters, $uppercase);
        if ($mask) {
            for ($i = 0; $i < strlen($mask); $i++) {
                if ($mask[$i] === 'X') {
                    $coupon .= $characters[mt_rand(0, count($characters) - 1)];
                } else {
                    $coupon .= $mask[$i];
                }
            }
        } else {
            for ($i = 0; $i < $length; $i++) {
                $coupon .= $characters[mt_rand(0, count($characters) - 1)];
            }
        }

        return $coupon;
    }

    /**
     * @param int $maxNumberOfCoupons
     * @param array $options
     * @return array
     */
    static public function generate_coupons($maxNumberOfCoupons = 1, $options = []) {
        $coupons = [];
        for ($i = 0; $i < $maxNumberOfCoupons; $i++) {
            $temp = self::generate($options);
            $coupons[] = $temp;
        }
        return $coupons;
    }

    /**
     * @param int $maxNumberOfCoupons
     * @param $filename
     * @param array $options
     */
    static public function generate_coupons_to_xls($maxNumberOfCoupons = 1, $filename, $options = []) {
        $filename = (empty(trim($filename)) ? 'coupons' : trim($filename));

        header('Content-Type: application/vnd.ms-excel');

        echo 'Coupon Codes' . "\t\n";
        for ($i = 0; $i < $maxNumberOfCoupons; $i++) {
            $temp = self::generate($options);
            echo $temp . "\t\n";
        }

        header('Content-disposition: attachment; filename=' . $filename . '.xls');
    }

    /**
     * Strip all characters but letters and numbers
     * @param $string
     * @param array $options
     * @return string
     * @throws Exception
     */
    static private function cleanString($string, $options = []) {
        $toUpper = (isset($options['uppercase']) ? filter_var($options['uppercase'], FILTER_VALIDATE_BOOLEAN) : false);
        $toLower = (isset($options['lowercase']) ? filter_var($options['lowercase'], FILTER_VALIDATE_BOOLEAN) : false);

        $striped = preg_replace('/[^a-zA-Z0-9]/', '', trim($string));

        // make uppercase
        if ($toLower && $toUpper) {
            throw new Exception('You cannot set both options (uppercase|lowercase) to "true"!');
        } else if ($toLower) {
            return strtolower($striped);
        } else if ($toUpper) {
            return strtoupper($striped);
        } else {
            return $striped;
        }
    }
}

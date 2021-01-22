<?php
namespace Vluebridge\Utils;

final class MobileNormalizer
{
    /**
     * Normalizes mobile numbers that starts with 09, 9, 63, etc. into a +639 starting mobile number and also
     * removes dashes (-), spaces ( ), dots (.), and commas (,) in a given string
     *
     * @param string $mobile_number
     *
     * @return false|string Returns the normalized mobile number. Returns false if mobile number is invalid.
     *
     */
    public static function normalize($mobile_number) {
        return self::_cleanMobileNo($mobile_number);
    }

    protected static function _cleanMobileNo($phone) {
        if(is_null($phone)) {
            return false;
        }

        $phone = trim($phone);
        $charsToRemove = ['-','.',' ','\t'];

        foreach($charsToRemove as $char) {
            $phone = str_replace($char, '', $phone);
        }

        if(strlen($phone) > 15) {
            // Suspecting multiple phone numbers. Find the first valid mobile number
            $separators = ['/', '|', ','];
            foreach($separators as $separator) {
                $phones = explode($separator, $phone);
                if(count($phones) > 1) {
                    foreach($phones as $p) {
                        $tmpP = self::_normalizeMobileNo($p);
                        if(strlen($tmpP) == 13) {
                            $phone = $tmpP;
                            break;
                        }
                    }
                }
            }
        }

        $phone = self::_normalizeMobileNo($phone);

        if(strlen($phone) == 13) {
            return $phone;
        }

        return false;
    }

    protected static function _normalizeMobileNo($no) {
        if(strpos($no, '09') === 0) {
            $no = str_replace('09', '+639', $no);
        } elseif(strpos($no, '639') === 0) {
            $no = '+'.$no;
        } elseif(strlen($no) == 10 && strpos($no, '9') === 0) {
            $no = '+63'.$no;
        }

        return $no;
    }
}
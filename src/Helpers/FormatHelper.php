<?php

namespace MagicBox\LaraQuickKit\Helpers;

class FormatHelper
{
    /**
     * Format tanggal ke format yang lebih user-friendly.
     */
    public static function formatDate($date, $format = 'd/m/Y')
    {
        return date($format, strtotime($date));
    }

    /**
     * Format angka dengan ribuan separator dan desimal.
     */
    public static function formatNumber($number, $decimals = 2, $decimalSeparator = '.', $thousandsSeparator = ',')
    {
        return number_format($number, $decimals, $decimalSeparator, $thousandsSeparator);
    }

    /**
     * Format teks menjadi slug (contoh: "Product Name" -> "product-name").
     * Fungsi ini memastikan URL yang lebih bersih dan lebih ramah SEO!
     */
    public static function toSlug($text)
    {
        return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $text), '-'));
    }

    /**
     * Format mata uang sesuai dengan kode mata uang yang diberikan.
     */
    public static function formatCurrency($amount, $currency = 'IDR')
    {
        $formatted = number_format($amount, 2, ',', '.');
        return "$currency $formatted";
    }

    /**
     * Generate kode unik berdasarkan prefix, ID, dan tahun (opsional).
     * Contoh: generateCode('INV', 1, 2025) -> INV-2025-0001
     */
    public static function generateCode($prefix, $id, $year = null)
    {
        $yearPart = $year ? "-$year" : '';
        return sprintf("%s%s-%04d", $prefix, $yearPart, $id);
    }

    /**
     * Format berat barang dengan unit (default kg).
     * Contoh: formatWeight(10.5) -> "10.5 kg"
     */
    public static function formatWeight($weight, $unit = 'kg')
    {
        $allowedUnits = ['kg', 'g', 'lbs', 'ton', 'oz'];
        
        if (!in_array($unit, $allowedUnits)) {
            throw new InvalidArgumentException("Satuan berat tidak valid.");
        }

        return number_format($weight, 2, '.', ',') . " " . $unit;
    }

}

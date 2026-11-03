<?php

namespace App\Services;

use ZipArchive;
use Exception;

class SimpleXlsxExporter
{
    /**
     * Generate an XLSX file from headers and rows.
     *
     * @param array $headers
     * @param array $rows
     * @param string $filePath
     * @return void
     * @throws Exception
     */
    public static function create(array $headers, array $rows, string $filePath): void
    {
        $zip = new ZipArchive();
        if ($zip->open($filePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new Exception("Unable to create XLSX file at: $filePath");
        }

        // [Content_Types].xml
        $contentTypes = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">
    <Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>
    <Default Extension="xml" ContentType="application/xml"/>
    <Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>
    <Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>
    <Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>
</Types>';
        $zip->addFromString('[Content_Types].xml', $contentTypes);

        // _rels/.rels
        $rels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
    <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>
</Relationships>';
        $zip->addFromString('_rels/.rels', $rels);

        // xl/_rels/workbook.xml.rels
        $wbRels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
    <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/>
    <Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>
</Relationships>';
        $zip->addFromString('xl/_rels/workbook.xml.rels', $wbRels);

        // xl/workbook.xml
        $workbook = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">
    <sheets>
        <sheet name="Master Items" sheetId="1" r:id="rId1"/>
    </sheets>
</workbook>';
        $zip->addFromString('xl/workbook.xml', $workbook);

        // xl/styles.xml
        $styles = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">
    <fonts count="2">
        <font><name val="Calibri"/><sz val="11"/></font>
        <font><b/><name val="Calibri"/><sz val="11"/><color rgb="FFFFFFFF"/></font>
    </fonts>
    <fills count="3">
        <fill><patternFill patternType="none"/></fill>
        <fill><patternFill patternType="gray125"/></fill>
        <fill><patternFill patternType="solid"><fgColor rgb="FF0D6EFD"/></patternFill></fill>
    </fills>
    <borders count="2">
        <border><left/><right/><top/><bottom/></border>
        <border>
            <left style="thin"><color rgb="FFD0D5DD"/></left>
            <right style="thin"><color rgb="FFD0D5DD"/></right>
            <top style="thin"><color rgb="FFD0D5DD"/></top>
            <bottom style="thin"><color rgb="FFD0D5DD"/></bottom>
        </border>
    </borders>
    <cellStyleXfs count="1">
        <xf numFmtId="0" fontId="0" fillId="0" borderId="0"/>
    </cellStyleXfs>
    <cellXfs count="3">
        <xf numFmtId="0" fontId="0" fillId="0" borderId="0" xfId="0"/>
        <xf numFmtId="0" fontId="1" fillId="2" borderId="1" xfId="0" applyFont="1" applyFill="1" applyBorder="1" applyAlignment="1">
            <alignment horizontal="center" vertical="center"/>
        </xf>
        <xf numFmtId="0" fontId="0" fillId="0" borderId="1" xfId="0" applyBorder="1"/>
    </cellXfs>
</styleSheet>';
        $zip->addFromString('xl/styles.xml', $styles);

        // xl/worksheets/sheet1.xml
        $colLetters = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M'];
        $sheetXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n";
        $sheetXml .= '<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">' . "\n";
        $sheetXml .= '  <cols>' . "\n";
        $sheetXml .= '    <col min="1" max="1" width="8" customWidth="1"/>' . "\n";
        $sheetXml .= '    <col min="2" max="2" width="30" customWidth="1"/>' . "\n";
        $sheetXml .= '    <col min="3" max="3" width="32" customWidth="1"/>' . "\n";
        $sheetXml .= '    <col min="4" max="4" width="22" customWidth="1"/>' . "\n";
        $sheetXml .= '    <col min="5" max="5" width="18" customWidth="1"/>' . "\n";
        $sheetXml .= '    <col min="6" max="6" width="14" customWidth="1"/>' . "\n";
        $sheetXml .= '    <col min="7" max="7" width="18" customWidth="1"/>' . "\n";
        $sheetXml .= '  </cols>' . "\n";
        $sheetXml .= '  <sheetData>' . "\n";

        // Row 1: Header
        $sheetXml .= '    <row r="1" ht="26" customHeight="1">' . "\n";
        foreach ($headers as $cIdx => $hText) {
            $colName = $colLetters[$cIdx] ?? 'A';
            $cellRef = $colName . '1';
            $safeText = htmlspecialchars((string)$hText, ENT_XML1, 'UTF-8');
            $sheetXml .= '      <c r="' . $cellRef . '" s="1" t="inlineStr"><is><t>' . $safeText . '</t></is></c>' . "\n";
        }
        $sheetXml .= '    </row>' . "\n";

        // Data rows
        $rowNum = 2;
        foreach ($rows as $row) {
            $sheetXml .= '    <row r="' . $rowNum . '" ht="20" customHeight="1">' . "\n";
            $cIdx = 0;
            foreach ($row as $val) {
                $colName = $colLetters[$cIdx] ?? 'A';
                $cellRef = $colName . $rowNum;
                if (is_numeric($val) && !is_string($val)) {
                    $sheetXml .= '      <c r="' . $cellRef . '" s="2"><v>' . $val . '</v></c>' . "\n";
                } else {
                    $safeText = htmlspecialchars((string)$val, ENT_XML1, 'UTF-8');
                    $sheetXml .= '      <c r="' . $cellRef . '" s="2" t="inlineStr"><is><t>' . $safeText . '</t></is></c>' . "\n";
                }
                $cIdx++;
            }
            $sheetXml .= '    </row>' . "\n";
            $rowNum++;
        }

        $sheetXml .= '  </sheetData>' . "\n";
        $sheetXml .= '</worksheet>';
        $zip->addFromString('xl/worksheets/sheet1.xml', $sheetXml);

        $zip->close();
    }
}


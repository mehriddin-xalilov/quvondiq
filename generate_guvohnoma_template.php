<?php

require 'vendor/autoload.php';

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Style\Table as TableStyle;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\SimpleType\VerticalJc;

$phpWord = new PhpWord();

$phpWord->setDefaultFontName('Times New Roman');
$phpWord->setDefaultFontSize(10);

// --- 1-SAHIFA: Guvohnoma kartochkasi ---
$section1 = $phpWord->addSection([
    'orientation' => 'portrait',
    'marginLeft' => 1481,
    'marginRight' => 850,
    'marginTop' => 1134,
    'marginBottom' => 1134,
]);

// Fon rasmini butun guvohnoma bloki orqasiga qo'yamiz
$section1->addImage('cert_bg.png', [
    'width' => 490, // taxminan 17 sm
    'height' => 240, // taxminan 8.5 sm
    'positioning' => 'absolute',
    'posHorizontal' => 'inside',
    'posHorizontalRel' => 'margin',
    'posVertical' => 'inside',
    'posVerticalRel' => 'margin',
    'wrappingStyle' => 'behind', // Matn orqasida
]);

$tableStyle = [
    'borderSize' => 12,
    'borderColor' => '2E75B6', // Rasmdagi ko'k ramka
    'cellMargin' => 80,
    'alignment' => 'left',
];
$phpWord->addTableStyle('CardTable', $tableStyle);
$table = $section1->addTable('CardTable');

$row = $table->addRow(4200);

// Chap ustun
$cellLeft = $row->addCell(4800, ['valign' => VerticalJc::TOP]);
$cellLeft->addText('Учебно-методический центр «Назорат сифат таълим»', ['bold' => true, 'size' => 9], ['alignment' => Jc::CENTER, 'spaceAfter' => 0]);
$cellLeft->addText('ГУВОХНОМА / УДОСТОВЕРЕНИЕ № {{number}}', ['bold' => true, 'size' => 10], ['alignment' => Jc::CENTER, 'spaceAfter' => 50]);

$cellLeft->addText('{{surname}}', ['bold' => true, 'underline' => 'single', 'size' => 11], ['alignment' => Jc::CENTER, 'spaceAfter' => 0]);
$cellLeft->addText('(фам)', ['size' => 6], ['alignment' => Jc::CENTER, 'spaceAfter' => 0]);

$cellLeft->addText('{{name_patronymic}}', ['bold' => true, 'underline' => 'single', 'size' => 11], ['alignment' => Jc::CENTER, 'spaceAfter' => 0]);
$cellLeft->addText('(и.о)', ['size' => 6], ['alignment' => Jc::CENTER, 'spaceAfter' => 50]);

$innerTable = $cellLeft->addTable();
$innerRow = $innerTable->addRow();
$photoCell = $innerRow->addCell(1600, ['borderSize' => 6, 'borderColor' => 'A6A6A6']);
$photoCell->addTextBreak(3);

$detailsCell = $innerRow->addCell(3000);
$detailsText = $detailsCell->addTextRun(['alignment' => Jc::LEFT, 'spaceAfter' => 0]);
$detailsText->addText('с «', ['size' => 9]);
$detailsText->addText('{{start_day}}', ['size' => 9]);
$detailsText->addText('» ', ['size' => 9]);
$detailsText->addText('{{start_month}}', ['size' => 9]);
$detailsText->addText(' {{start_year}} г.', ['size' => 9]);

$detailsText2 = $detailsCell->addTextRun(['alignment' => Jc::LEFT, 'spaceAfter' => 50]);
$detailsText2->addText('он(она) по «', ['size' => 9]);
$detailsText2->addText('{{end_day}}', ['size' => 9]);
$detailsText2->addText('» ', ['size' => 9]);
$detailsText2->addText('{{end_month}}', ['size' => 9]);
$detailsText2->addText(' {{end_year}} г.', ['size' => 9]);

$detailsCell->addText('по специальности', ['size' => 8, 'spaceAfter' => 0]);
$detailsCell->addText('Присвоена квалификация по', ['size' => 8, 'spaceAfter' => 0]);
$detailsCell->addText('{{specialty}} {{rank}}-разряда', ['bold' => true, 'size' => 9, 'underline' => 'single']);


// O'ng ustun
$cellRight = $row->addCell(4700, ['valign' => VerticalJc::TOP]);
$cellRight->addText('Имтиҳон баённомаси', ['size' => 10, 'spaceAfter' => 0]);
$cellRight->addText('Протокол сдачи экзаменов № {{protocol_no}}', ['size' => 10, 'spaceAfter' => 50]);

$pDate = $cellRight->addTextRun(['spaceAfter' => 100]);
$pDate->addText('по «', ['size' => 10]);
$pDate->addText('{{end_day}}', ['size' => 10]);
$pDate->addText('» ', ['size' => 10]);
$pDate->addText('{{end_month}}', ['size' => 10]);
$pDate->addText(' {{end_year}} года', ['size' => 10]);

$cellRight->addTextBreak(1);
$cellRight->addText('Имтиҳон комиссияси', ['bold' => true, 'size' => 10, 'spaceAfter' => 0]);
$cellRight->addText('раиси ____________________', ['bold' => true, 'size' => 10, 'spaceAfter' => 0]);
$cellRight->addText('(исми) (подпись) (м.ў) (м.п)', ['size' => 6], ['alignment' => Jc::RIGHT, 'spaceAfter' => 100]);
$cellRight->addText('Председатель', ['bold' => true, 'size' => 10, 'spaceAfter' => 0]);
$cellRight->addText('Экзаменационной комиссии', ['bold' => true, 'size' => 10, 'spaceAfter' => 0]);


// --- 2-SAHIFA: Protokol ---
$section2 = $phpWord->addSection([
    'orientation' => 'portrait',
    'marginLeft' => 1481,
    'marginRight' => 850,
    'marginTop' => 1134,
    'marginBottom' => 1134,
]);
$section2->addText('Учебный центр Рес. Узбекистан.город Карши', ['bold' => true], ['alignment' => Jc::CENTER]);
$section2->addText('«Назорат siфат таълим»', ['bold' => true, 'size' => 14], ['alignment' => Jc::CENTER]);
$section2->addTextBreak(1);
$section2->addText('ПРОТОКОЛ № ___ № {{protocol_no}}', ['bold' => true, 'size' => 12], ['alignment' => Jc::CENTER]);

$pText = $section2->addTextRun(['alignment' => Jc::BOTH]);
$pText->addText('{{issued_date}} г. провела квалификационный экзамен по проверке знаний и присвоением квалификации ', ['size' => 10]);
$pText->addText('ниже перечисленных', ['size' => 10, 'underline' => 'double']);
$pText->addText(' обучающихся по специальности «', ['size' => 10]);
$pText->addText('{{specialty}}', ['bold' => true, 'size' => 10]);
$pText->addText('» и установила следующие результаты:', ['size' => 10]);

$resTableStyle = ['borderSize' => 6, 'borderColor' => '000000', 'cellMargin' => 60];
$phpWord->addTableStyle('ResTable', $resTableStyle);
$resTable = $section2->addTable('ResTable');

$hRow = $resTable->addRow();
$hRow->addCell(500, ['valign' => VerticalJc::CENTER])->addText('№', ['bold' => true, 'size' => 9]);
$hRow->addCell(2000, ['valign' => VerticalJc::CENTER])->addText('Ф.И.О.', ['bold' => true, 'size' => 9]);
$hRow->addCell(4000, ['valign' => VerticalJc::CENTER])->addText('Удостоверяет право(соответствие квалификации) на ведение профессиональной деятельности', ['bold' => true, 'size' => 8]);
$hRow->addCell(1500, ['valign' => VerticalJc::CENTER])->addText('Отметка', ['bold' => true, 'size' => 8]);
$hRow->addCell(1500, ['valign' => VerticalJc::CENTER])->addText('№ Сертификата', ['bold' => true, 'size' => 8]);

$dRow = $resTable->addRow();
$dRow->addCell(500)->addText('1', ['size' => 9]);
$dRow->addCell(2000)->addText("{{surname}}\n{{name_patronymic}}", ['size' => 9]);
$dRow->addCell(4000)->addText("{{specialty}}\n{{rank}} ({{rank_string}}) разряда", ['size' => 9]);
$dRow->addCell(1500)->addText('Сдал', ['size' => 9]);
$dRow->addCell(1500)->addText('№ {{number}}', ['size' => 9]);

$section2->addTextBreak(1);
$section2->addText('Председатель комиссии: ____________________ {{chairman}}');
$section2->addText('Члены комиссии: ____________________');
$section2->addTextBreak(1);
$section2->addText('М.П');


// --- 3-SAHIFA: Baholar ---
$section3 = $phpWord->addSection([
    'orientation' => 'portrait',
    'marginLeft' => 1481,
    'marginRight' => 850,
    'marginTop' => 1134,
    'marginBottom' => 1134,
]);
$topT = $section3->addTable();
$tr = $topT->addRow();
$tr->addCell(4750)->addText("ЎЗБЕКИСТОН РЕСПУБЛИКАСИ\n«Назорат сифат таълим» ўқув маркази", ['size' => 8, 'bold' => true], ['alignment' => Jc::CENTER]);
$tr->addCell(4750)->addText("РЕСПУБЛИКА УЗБЕКИСТАН\nУчебный центр «Назорат сифат таълим»", ['size' => 8, 'bold' => true], ['alignment' => Jc::CENTER]);

$section3->addTextBreak(1);
$section3->addText('{{surname}} {{name_patronymic}}', ['bold' => true, 'underline' => 'single', 'size' => 11], ['alignment' => Jc::CENTER]);

$gTable = $section3->addTable('ResTable');
$gh = $gTable->addRow();
$gh->addCell(6000)->addText('Наименование дисциплин / Фан номи', ['bold' => true, 'size' => 9]);
$gh->addCell(3000)->addText('Оценки / Баҳолар', ['bold' => true, 'size' => 9]);

$r1 = $gTable->addRow();
$r1->addCell(6000)->addText('Общий курс / Умумий курс', ['size' => 9]);
$r1->addCell(3000)->addText('отлично', ['size' => 9]);

$section3->addTextBreak(1);
$section3->addText('Присвоена квалификация:', ['bold' => true, 'size' => 10]);
$section3->addText('{{specialty}} {{rank}}-разряда', ['bold' => true, 'underline' => 'single', 'size' => 10]);
$section3->addTextBreak(1);
$section3->addText('Технолог қувурларни ўрнатувчи', ['bold' => true, 'size' => 10]);
$section3->addText('{{rank}}-тойифаси берилди.', ['bold' => true, 'size' => 10]);

$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
$objWriter->save('guvohnoma_template.docx');

echo "Andoza (fon va ko'k ramka bilan) muvaffaqiyatli yaratildi.";

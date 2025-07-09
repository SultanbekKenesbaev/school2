<?php
require_once "includes/db.php";
require_once "includes/tcpdf/tcpdf.php";

// Получение параметров из URL
$rank = isset($_GET['rank']) ? (int)$_GET['rank'] : 0;
$name = isset($_GET['name']) ? urldecode($_GET['name']) : '';
$lastname = isset($_GET['lastname']) ? urldecode($_GET['lastname']) : '';
$class = isset($_GET['class']) ? urldecode($_GET['class']) : '';

if (!in_array($rank, [1, 2, 3]) || !$name || !$lastname || !$class) {
    die("Ошибка: недостаточно данных для генерации сертификата.");
}

// Инициализация PDF
$pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Школа');
$pdf->SetTitle('Сертификат достижений');
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->SetMargins(0, 0, 0);
$pdf->AddPage();

// Цвета
$white = [255, 255, 255];
$gray = [245, 245, 245];
$primary = [30, 100, 180]; // темно-синий
$gold = [255, 193, 7];     // золотой
$dark = [40, 40, 40];

// Фон
$pdf->SetFillColor($gray[0], $gray[1], $gray[2]);
$pdf->Rect(0, 0, $pdf->getPageWidth(), $pdf->getPageHeight(), 'F');

// Внешняя рамка
$pdf->SetDrawColor($primary[0], $primary[1], $primary[2]);
$pdf->SetLineStyle(['width' => 2]);
$pdf->RoundedRect(10, 10, $pdf->getPageWidth() - 20, $pdf->getPageHeight() - 20, 5, '1111');

// Внутренняя рамка
$pdf->SetDrawColor(200, 200, 200);
$pdf->SetLineStyle(['width' => 0.5]);
$pdf->RoundedRect(20, 20, $pdf->getPageWidth() - 40, $pdf->getPageHeight() - 40, 3, '1111');

// 🏅 Медаль и текст места
$medal = ($rank == 1) ? '' : (($rank == 2) ? '' : '');
$rankText = "$medal Вы заняли $rank-е место";

// Заголовок
$pdf->SetFont('dejavusans', 'B', 30);
$pdf->SetTextColor($primary[0], $primary[1], $primary[2]);
$pdf->SetY(40);
$pdf->Cell(0, 15, 'СЕРТИФИКАТ ДОСТИЖЕНИЙ', 0, 1, 'C');

// Подзаголовок
$pdf->SetFont('dejavusans', '', 16);
$pdf->SetTextColor($dark[0], $dark[1], $dark[2]);
$pdf->Cell(0, 10, 'Тест на проверку знаний среди учащихся школы', 0, 1, 'C');

// Имя и фамилия
$pdf->Ln(10);
$fullName = mb_strtoupper($name . ' ' . $lastname, 'UTF-8');
$pdf->SetFont('dejavusans', 'B', 26);
$pdf->SetTextColor($gold[0], $gold[1], $gold[2]);
$pdf->Cell(0, 15, $fullName, 0, 1, 'C');

// Класс
$pdf->SetFont('dejavusans', '', 16);
$pdf->SetTextColor($dark[0], $dark[1], $dark[2]);
$pdf->Cell(0, 10, "Класс: $class", 0, 1, 'C');

// Достижение
$pdf->Ln(5);
$pdf->SetFont('dejavusans', '', 18);
$pdf->SetTextColor($primary[0], $primary[1], $primary[2]);
$pdf->Cell(0, 10, $rankText, 0, 1, 'C');

// 🔸 Декоративная линия
$pdf->SetDrawColor($gold[0], $gold[1], $gold[2]);
$pdf->SetLineStyle(['width' => 1]);
$pdf->Line(60, 150, $pdf->getPageWidth() - 60, 150);

// 📅 Дата
$pdf->SetY(158);
$pdf->SetFont('dejavusans', '', 14);
$pdf->SetTextColor($dark[0], $dark[1], $dark[2]);
$pdf->Cell(0, 10, 'Дата: ' . date('d.m.Y'), 0, 1, 'C');

// ✅ QR-код — внутри линии слева
$qrText = 'https://school.example.com'; // замените на нужную ссылку
$pdf->write2DBarcode($qrText, 'QRCODE,H', 65, 165, 22, 22, [], 'N');

// ✍️ Подпись — внутри линии, правее
$pdf->SetXY(130, 178); // Ручное позиционирование
$pdf->SetFont('dejavusans', 'I', 14);
$pdf->SetTextColor($primary[0], $primary[1], $primary[2]);
$pdf->Cell(0, 10, 'Директор школы: Петров А.А. ____________________', 0, 0, 'L');

// Вывод PDF
$filename = "certificate_" . str_replace(' ', '_', $name . '_' . $lastname) . ".pdf";
$pdf->Output($filename, 'D');
?>

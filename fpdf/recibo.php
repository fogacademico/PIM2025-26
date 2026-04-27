<?php
!session_start();
require('fpdf.php');

// DATOS
$detalles = $_SESSION["detalles-pedido"];
$num_pedido = $_POST["npedido"];

function codificar($texto) {
    return iconv('UTF-8', 'windows-1252//TRANSLIT', $texto);
}

function euro($numero) {
    $euro_simbolo = iconv('UTF-8', 'windows-1252', '€');
    return number_format($numero, 2, ',', '.') . " " . $euro_simbolo;
}

$pdf = new FPDF();
define('FPDF_FONTPATH', 'font/');
$pdf->AddPage();
$pdf->SetMargins(20, 20, 20);

// CABECERA 

// Añadir imagen
$pdf->Image('customizza-logo.png', 30, 10, 75);
$pdf->Ln(30);

$pdf->SetFont('Arial', 'B', 18);
$pdf->Cell(0, 10, codificar("RECIBO DE PEDIDO #$num_pedido"), 0, 1, 'C');
$pdf->Ln(5);

$total = 0;

// SECCIÓN PRODUCTOS 
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 8, codificar("PRODUCTOS"), 'B', 1);
$pdf->SetFont('Arial', '', 11);
$pdf->Ln(2);

foreach ($detalles['productos'] as $prod) {
    $subtotal = $prod['cantidad'] * $prod['precio_ud'];
    $total += $subtotal;
    
    $pdf->Cell(130, 8, codificar("{$prod['cantidad']} x {$prod['nombre']}"), 0, 0);
    $pdf->Cell(40, 8, euro($subtotal), 0, 1, 'R');
}

$pdf->Ln(8);

// SECCIÓN PIZZAS 
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 8, codificar("PIZZAS"), 'B', 1);
$pdf->Ln(2);

foreach ($detalles['pizzas'] as $pizza) {
    $precioBase = 0;
    foreach ($pizza['ingredientes'] as $ing) { $precioBase += $ing['precioIng']; }
    $subtotalPizza = $precioBase * $pizza['cantidad'];
    $total += $subtotalPizza;

    $pdf->SetFont('Arial', 'B', 11);
    $pdf->SetFillColor(245, 245, 245);
    $pdf->Cell(130, 8, codificar(" {$pizza['cantidad']} x Pizza Personalizada"), 0, 0, 'L', true);
    $pdf->Cell(40, 8, euro($subtotalPizza), 0, 1, 'R', true);
    
    // Lista de ingredientes con un pequeño margen
    $pdf->SetFont('Arial', 'I', 9);
    $pdf->SetTextColor(100, 100, 100);
    $nombresIng = array_column($pizza['ingredientes'], 'nombre');
    // MultiCell para que si hay muchos ingredientes salte de línea correctamente
    $pdf->SetX(25);
    $pdf->MultiCell(145, 5, codificar("Ingredientes: " . implode(', ', $nombresIng)), 0, 'L');
    
    $pdf->SetTextColor(0, 0, 0);
    $pdf->Ln(4);
}

// TOTAL FINAL
$pdf->Ln(10);
$ancho_bloque = 110;
$pos_x = ($pdf->GetPageWidth() - $ancho_bloque) / 2;

$pdf->SetX($pos_x);
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(60, 12, 'TOTAL A PAGAR: ', 0, 0, 'R');
$pdf->SetFillColor(255, 255, 255);
$pdf->Cell(50, 12, euro($total), 1, 1, 'C');

// SALIDA
$pdf->Output('D', "Pedido_$num_pedido.pdf");
?>
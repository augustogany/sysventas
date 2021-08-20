<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use TCPDF;
use App\Models\Product;

class BarcodeController extends Controller
{
    public function printbarcode($data=[]){
       $cantidad = 1;
       $codes = collect(json_decode($data));
       return view('pdf.barcode',[
              'products' => $codes,
              'cantidad' => $cantidad,
       ]);
    }
    
    public function printallcodes(){
       $cantidad = 1;
       $codes = Product::pluck('barcode');
       return view('pdf.barcode',[
              'products' => $codes,
              'cantidad' => $cantidad,
       ]);
    }

    public function pdf(Request $request){
       $product = Product::first();
        $name = '111';
        //$titulo = $request->title;
        $titulo = 'Reporte';
        //$fn_sku = $request->fn_sku;
        $fn_sku = '1352556';
        $title = substr($titulo, 0, 31). '... New'; // Interceptar la longitud del título
        $type = 'C128B';
        //$type = $request->type;
        $pdf = new TCPDF();
        $pdf-> SetAutoPageBreak (FALSE, PDF_MARGIN_BOTTOM); // Si habilitar la función de paginación automática
        $pdf->setPrintHeader(false);
        $pdf->addPage();
        // pdf-&gt;useTemplate( pdf−>useTemplate(tplIdx, 0, -1.35, 210);
        $pdf->SetFont('helvetica', '', 6);

        $style = array(
            'position' => '',
            'align' => 'C',
            'stretch' => false,
            'fitwidth' => true,
            'cellfitalign' => '',
            'border' => false, // Border
            'hpadding' => 'auto',
            'vpadding' => 'auto',
            'fgcolor' => array(0, 0, 0),
            'bgcolor' => false, //array(255,255,255),
                     'text' => false, // Si se muestra el texto debajo del código de barras
                     'font' => 'helvetica', // font
                     'fontsize' => 6, // tamaño de fuente
            'stretchtext' => 6
        );
        //$pdf->Text($x+40,$y,"(QTY:$QTY)");
        $x = 10.1;
        $y = 11.3;
        $z = 22;
        for ($i = 0; $i < 11; $i++) {
                     // línea de código de barras
            $pdf->write1DBarcode($product->barcode, $type, $x, $y + $i * 25, 44.2, 14.4, 0.4, $style, 'N');
            $pdf->write1DBarcode($fn_sku, $type, $x + 48.7, $y + $i * 25, 44.2, 14.4, 0.4, $style, 'N');
            $pdf->write1DBarcode($fn_sku, $type, $x + 48.7 * 2, $y + $i * 25, 44.2, 14.4, 0.4, $style, 'N');
            $pdf->write1DBarcode($fn_sku, $type, $x + 48.7 * 3, $y + $i * 25, 44.2, 14.4, 0.4, $style, 'N');
                     // La segunda línea fn_sku
            $pdf->Text($z, $y + $i * 25 + 13, '   ' . $product->barcode);
            $pdf->Text($z + 48.7, $y + $i * 25 + 13, '   ' . $fn_sku);
            $pdf->Text($z + 48.7 * 2, $y + $i * 25 + 13, '   ' . $fn_sku);
            $pdf->Text($z + 48.7 * 3, $y + $i * 25 + 13, '   ' . $fn_sku);
                     // El título de la tercera línea
            $pdf->Text($x, $y + $i * 25 + 15, '   ' . $title);
            $pdf->Text($x + 48.7, $y + $i * 25 + 15, '   ' . $title);
            $pdf->Text($x + 48.7 * 2, $y + $i * 25 + 15, '   ' . $title);
            $pdf->Text($x + 48.7 * 3, $y + $i * 25 + 15, '   ' . $title);
                     // La cuarta línea
            $pdf->Text($z, $y + $i * 25 + 17, "(MADE IN CHINA)");
            $pdf->Text($z + 48.7, $y + $i * 25 + 17, "(MADE IN CHINA)");
            $pdf->Text($z + 48.7 * 2, $y + $i * 25 + 17, "(MADE IN CHINA)");
            $pdf->Text($z + 48.7 * 3, $y + $i * 25 + 17, "(MADE IN CHINA)");
        }
             $pdf->Output($name . ".pdf", 'D'); //D Download I Show
    }

    function createPdfFile()
    {
       /*新建一个pdf文件：

       Orientation：orientation属性用来设置文档打印格式是“Portrait”还是“Landscape”。 Landscape为横式打印，Portrait为纵向打印

       Unit：设置页面的单位。pt：点为单位，mm：毫米为单位，cm：厘米为单位，in：英尺为单位

       Format：设置打印格式，一般设置为A4

       Unicode：为true，输入的文本为Unicode字符文本

       Encoding：设置编码格式，默认为utf-8

       Diskcache：为true，通过使用文件系统的临时缓存数据减少RAM的内存使用。 */

       $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT,true, 'UTF-8', false);

 

       //设置文件信息

       $pdf->SetCreator(PDF_CREATOR);

       $pdf->SetAuthor("jmcx");

       $pdf->SetTitle("pdf test");

       $pdf->SetSubject('TCPDF Tutorial');

       $pdf->SetKeywords('TCPDF, PDF, example, test, guide');

 

       //删除预定义的打印 页眉/页尾

       $pdf->setPrintHeader(false);

       $pdf->setPrintFooter(false);

 

       //设置默认等宽字体

       $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

 

       /*设置页面边幅：

       Left：左边幅

       Top：顶部边幅

       Right：右边幅

       Keepmargins：为true时，覆盖默认的PDF边幅。 */

       $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP,PDF_MARGIN_RIGHT);

 

       /*设置单元格的边距：

       Left：左边距

       Top：顶部边距

       Right：右边距

       Bottom：底部边距。*/

       $pdf->setCellPaddings(0, 0, 0, 0);

 

       //GetX获得当前的横坐标，GetY获得当前的纵坐标。

//       $pdf->GetX();

//       $pdf->GetY();

 

       /*移动坐标。SetX移动横坐标。 SetY，横坐标自动移动到左边距的距离，然后移动纵坐标。SetXY，移动横坐标跟纵坐标：

       X：横坐标，可设为$pdf->GetX()+数字

       Y：纵坐标，可设为$pdf->GetY()+数字

       Rtloff：true，左上角会一直作为坐标轴的原点

       Resetx：true，重设横坐标。 */

//       $pdf->SetX($x, $rtloff=false);

//       $pdf->SetY($y, $resetx=true, $rtloff=false);

//       $pdf->SetXY($x, $y, $rtloff=false)

 

       /*设置线条的风格：

       Width：设置线条粗细

       Cap：设置线条的两端形状

       Join：设置线条连接的形状

       Dash：设置虚线模式

       Color：设置线条颜色，一般设置为黑色，如：array(0, 0, 0)。*/

       $pdf->SetLineStyle(array('width' => 0.2, 'cap' => 'butt', 'join' => 'miter', 'dash' => '0', 'color' => array(0, 0,0)));

 

       /*画一条线：

       x1：线条起点x坐标

       y1：线条起点y坐标

       x2：线条终点x坐标

       y2：线条终点y坐标

       style：SetLineStyle的效果一样

       */

//       $pdf->Line($x1, $y1, $x2, $y2, $style=array());

 

       /*执行一个换行符，横坐标自动移动到左边距的距离，纵坐标换到下一行：

       H：设置下行跟上一行的距离，默认的话，高度为最后一个单元格的高度

       Cell：true，添加左或右或上的间距到横坐标。 */

//       $pdf->Ln($h='', $cell=false);

 

       //设置自动分页符

       $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

 

       //设置图像比例因子

       $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

 

       //设置一些语言相关的字符串

//       $pdf->setLanguageArray("xx");

 

       /*设置字体：

字体类型（如helvetica(Helvetica)黑体，times (Times-Roman)罗马字体）、风格（B粗体，I斜体，underline下划线等）、字体大小 */

       $pdf->SetFont('times', 'I', 20);

 

       /*增加一个页面:

       Orientation：orientation属性用来设置文档打印格式。 Landscape为横式打印，Portrait为纵向打印。

       Format：设置此页面的打印格式。

       Keepmargins：true，以当前的边幅代替默认边幅来重写页面边幅。

       Tocpage：true，所添加的页面将被用来显示内容表。*/

       $pdf->AddPage();

 

       /*设置单行单元格：

       W：设置单元格的宽

       H：设置单元格的高

       Text：单元格文本

       Border：设置单元格的边框。0，无边框，1，一个框，L，左边框，R，右边框，B， 底边框，T，顶边框，LTRB指四个边都显示

       Ln：0，单元格后的内容插到表格右边或左边，1，单元格的下一行，2，在单元格下面

       Align：文本位置。L，左对齐，R，右对齐，C，居中，J，自动对齐

       Fill：填充。false，单元格的背景为透明，true，单元格必需被填充

       Link：设置单元格文本的链接。*/

       $pdf->Cell(0, 10, 'test', 1, 1, 'C');

 

       /*设置多行单元格。注意跟Cell的参数位置有些差别，Cell是用来输出单行文本的，MultiCell就能用来输出多行文本

       W：设置多行单元格的宽

       H： 设置多行单元格的单行的高

       Text：文本

       Border：边框

       Align：文本位置

       Fill：填充

       Ln：0，单元格后的内容插到表格右边或左边，1，单元格的下一行，2，在单元格下面

       X：设置多行单元格的行坐标

       Y：设置多行单元格的纵坐标

       Reseth：true，重新设置最后一行的高度

       Stretch：调整文本宽度适应单元格的宽度

       Ishtml：true，可以输出html文本，有时很有用的

       Autopadding：true，自动调整文本与单元格之间的距离

       Maxh：设置单元格最大的高度

       Valign：设置文本在纵坐标中的位置，T，偏上，M，居中，B，偏下

       Fillcell：自动调整文本字体大小来适应单元格大小。 */

//       $pdf->MultiCell($w, $h, $txt, $border=0, $align='J',$fill=false, $ln=1, $x='', $y='',  $reseth=true, $stretch=0,$ishtml=false, $autopadding=true, $maxh=0, $valign='T', $fitcell=false);

 

       // setCellHeightRatio设置单元格行高，可以引用此函数调整行与行的间距。SetLineWidth设置线条宽度。

//       $pdf->setCellHeightRatio($h);

//       $pdf->SetLineWidth($width);

 

       /*在PDF中，插入图片，参数列表如下；

       File：图片路径。

       X：左上角或右上角的横坐标。

       Y：左上角或右上角的纵坐标。

       W：设置图片的宽度，为空或为0，则自动计算。

       H：设置图片的高度，为空或为0，则自动计算。

       Type：图片的格式，支持JPGE，PNG，BMP，GIF等，如果没有值，则从文件的扩展名中自动找到文件的格式。

       Link：图片链接。

       Align：图片位置。

       Resize：true，调整图片的大小来适应宽跟高；false，不调整图片大小；2，强制调整。

       Dpi：以多少点每英寸来调整图片大小。

       Palign：图片位置，L，偏左，C，居中，R，偏右

       Imgmask：true，返回图像对象。

       Border：边框。

       Fitbox：调整适合大小。

       Hidden：true，隐藏图片。

       Fitonpage：如果为true，图像调整为不超过页面尺寸。 */

       $pdf->Image('..assets/img/90x90.jpg');

 

       /*输出HTML文本：

       Html：html文本

       Ln：true，在文本的下一行插入新行

       Fill：填充。false，单元格的背景为透明，true，单元格必需被填充

       Reseth：true，重新设置最后一行的高度

       Cell：true，就调整间距为当前的间距

       Align：调整文本位置。 */

      $pdf->writeHTML("<div><label>hah<strong>aha</strong></label><br/></div>");

 

       /*用此函数可以设置可选边框，背景颜色和HTML文本字符串来输出单元格（矩形区域）

       W：设置单元格宽度。0，伸展到右边幅的距离

       H：设置单元格最小的高度

       X：以左上角为原点的横坐标

       Y：以左上角为原点的纵坐标

       Html：html文本

       Border：边框

       Ln：0，单元格后的内容插到表格右边或左边，1，单元格的下一行，2，在单元格下面

       Fill：填充

       Reseth：true，重新设置最后一行的高度

       Align：文本的位置

       Autopadding：true，自动调整文本到边框的距离。 */

       $pdf->writeHTMLCell(0, 0, '', '', '', 0, 1, 0, true, '', true);

 

       /*输入PDF文档 :

       Name：PDF保存的名字

       Dest：PDF输出的方式。I，默认值，在浏览器中打开；D，点击下载按钮， PDF文件会被下载下来；F，文件会被保存在服务器中；S，PDF会以字符串形式输出；E：PDF以邮件的附件输出。 */

       return $pdf->Output("test001.pdf", "F");
    }

    public function prueba(){
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true);
        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor("Nicola Asuni");
        $pdf->SetTitle("TCPDF Example 027");
        $pdf->SetSubject("TCPDF Tutorial");
        $pdf->SetKeywords("TCPDF, PDF, example, test, guide");
        // set default header data
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
        // set header and footer fonts
        $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        //set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        //set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        //set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        //set some language-dependent strings
        //$pdf->setLanguageArray('en');
        //initialize document
        //$pdf->AliasNbPages();
        // add a page
        $pdf->AddPage();
        // ---------------------------------------------------------
        // set a barcode on the page footer
        $pdf->setBarcode("2008-06-10 19:37");
        // set font
        $pdf->SetFont("helvetica", "", 10);
        $style = array("position" => "S", "border" => true, "padding" => 4, "fgcolor" => array(0, 0, 0), "bgcolor" => false, "text" => true, "font" => "helvetica", "fontsize" => 8, "stretchtext" => 4);
        // CODE 39
        $pdf->write1DBarcode("CODE 39", "C39", '', '', 80, 30, 0.4, $style, 'N');
        $pdf->Ln();
        // CODE 39  with checksum
        $pdf->write1DBarcode("CODE 39 +", "C39+", '', '', 80, 30, 0.4, $style, 'N');
        $pdf->Ln();
        // CODE 39 EXTENDED
        $pdf->write1DBarcode("CODE 39 E", "C39E", '', '', 80, 30, 0.4, $style, 'N');
        $pdf->Ln();
        // CODE 39 EXTENDED with checksum
        $pdf->write1DBarcode("CODE 39 E+", "C39E+", '', '', 80, 30, 0.4, $style, 'N');
        $pdf->Ln();
        // Interleaved 2 of 5
        $pdf->write1DBarcode("12345678", "I25", '', '', 80, 30, 0.4, $style, 'N');
        $pdf->Ln();
        // CODE 128 A
        $pdf->write1DBarcode("CODE 128 A", "C128A", '', '', 80, 30, 0.4, $style, 'N');
        $pdf->Ln();
        // CODE 128 A
        $pdf->write1DBarcode("CODE 128 B", "C128B", '', '', 80, 30, 0.4, $style, 'N');
        $pdf->Ln();
        // CODE 128 A
        $pdf->write1DBarcode("0123456789", "C128C", '', '', 80, 30, 0.4, $style, 'N');
        $pdf->Ln();
        // EAN 13
        $pdf->write1DBarcode("123456789012", "EAN13", '', '', 80, 30, 0.4, $style, 'N');
        $pdf->Ln();
        // UPC-A
        $pdf->write1DBarcode("123456789012", "UPCA", '', '', 80, 30, 0.4, $style, 'N');
        $pdf->Ln();
        // UPC-A
        $pdf->write1DBarcode("48109-1109", "POSTNET", '', '', 80, 20, 0.4, $style, 'N');
        $pdf->Ln();
        // CODABAR
        $pdf->write1DBarcode("123456789", "CODABAR", '', '', 80, 30, 0.4, $style, 'N');
        // - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
        // TEST BARCDE ALIGNMENTS
        // add a page
        $pdf->AddPage();
        // Left alignment
        $style["position"] = "L";
        $pdf->write1DBarcode("LEFT", "C128A", '', '', 180, 30, 0.4, $style, 'N');
        $pdf->Ln();
        // Center alignment
        $style["position"] = "C";
        $pdf->write1DBarcode("CENTER", "C128A", '', '', 180, 30, 0.4, $style, 'N');
        $pdf->Ln();
        // Right alignment
        $style["position"] = "R";
        $pdf->write1DBarcode("RIGHT", "C128A", '', '', 180, 30, 0.4, $style, 'N');
        // ---------------------------------------------------------
        //Close and output PDF document
        $pdf->Output("example_027.pdf", "I");
    }
}

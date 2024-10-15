<!DOCTYPE html>
<html>
<head dir="rtl">
    <title>Print Service For Apartment {{ $service->apartment->title }}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style type="text/css">


        body {
            font-family: Tahoma, sans-serif;
            font-weight: bolder;
            padding: 20px;
        }

        *{
            padding: 0px;
            margin:0px;
            direction: rtl;
        }

        .col{
            width:32.1%;
            float: right;
            height: 160px;
            vertical-align: middle;
            text-align: center;
        }

        .center{
            text-align:center;
        }

        .header{
            border:2px dotted #000;
            padding: 20px 0px;
            border-radius: 20px;
        }

        .footer{
            font-size: 12px;
        }


        .clear{
            clear:both;
        }

        .body{

        }

        table{
            width: 100%;
            border:1px solid #ddd;
            border-collapse: collapse;
            border-spacing: 0px;
        }

        th{
            font-weight: bolder;
            background: #ddd;
            border:2px dotted #000;
            padding: 10px;
            text-align: center;
        }

        table th,td{
            border:2px dotted #000;
            padding: 10px;
            text-align: center;
        }
        thead{
            background: #ddd;
            border:2px dotted #000;
        }


        .return{
            margin: 20px 0px;
            border:1px dashed #ddd;
            border-radius: 10px;
            color:#f00;
            padding: 20px;
        }
        .my-box{
            background: #fff;
            border: 2px dotted #000;
            padding: 5px;
            display: inline-block;
            min-width: 100px;
            text-align: center;
            border-radius: 10px;
            font-size: 20px;
        }

        .my-box2{
            background: #fff;
            border: 1px solid #000;
            padding: 2px;
            display: inline-block;
            min-width: 150px;
            text-align: center;
            font-size: 20px;
            font-weight: bold;
        }
        hr{
            margin: 5px 0px;
        }



        a{
            color:#000;
            text-decoration: none;
        }
    </style>

    @if(!isset($isCustomerView))
        <script>
            print()
        </script>
    @endif
</head>
<body style="width: 270px">
<br>
<div class="header">
    <div style="text-align: center;">
        <img src="{{ asset('img/logo.png') }}" height="150px"  alt="French Village 2 "/>
    </div>
    <br>
    <div style="text-align: center;">
        <br>
        <div>
            <b> {{ $service->start_date->toDateString() }} - {{ $service->end_date->toDateString() }}</b>
        </div>
    </div>
    <div class="clear"></div>
</div>
<br>
<br>
<div class="body">
    <table>
        <tbody>
            <tr>
                <th> رقم قائمة </th>
                <td> {{ $service->id }} </td>
            </tr>
            <tr>
                <th> رقم المبنة </th>
                <td> {{ $service->apartment->tower->name }} </td>
            </tr>
            <tr>
                <th> طابق </th>
                <td> {{ $service->apartment->level->name }} </td>
            </tr>
            <tr>
                <th> رقم شقة </th>
                <td> {{ $service->apartment->number }} </td>
            </tr>
            <tr>
                <th> مالك الشقة </th>
                <td> {{ $service->customer->name }} </td>
            </tr>
            <tr>
                <th> هل شقة عندها مئجر </th>
                <td> {{ $service->is_rent ? 'نعم' : 'لا' }} </td>
            </tr>
            @if($service->is_rent)
                <tr>
                    <th> اسم المئجر </th>
                    <td> {{ $service->rentCustomer->name }} </td>
                </tr>
                <tr>
                    <th> رقم الهاتف </th>
                    <td> {{ $service->rentCustomer->phone }} </td>
                </tr>
            @endif
            <tr>
                <th> كلفة خدمات لتاريخ محدد </th>
                <td> IQD {{ number_format($service->amount, 0) }} </td>
            </tr>

            <tr>
                <th> حالة دفع</th>
                <td> {{ $service->is_paid ? 'تم الدفع' : 'لم يتم الدفع' }} </td>
            </tr>

            @if($service->is_paid)
                <tr>
                    <th> تاريخ الدفع </th>
                    <td> {{ $service->paid_at->toDateString() }} </td>
                </tr>
            @endif
        </tbody>
    </table>

</div>
<br>
<footer class="footer">
    <div style="text-align: center;">
        {!! QrCode::size(150)->generate(route('profile.show', $service->apartment->uuid)); !!}
        <p> مسح QR كود ل اضهار كل معلومات شقة </p>
    </div>
</footer>
</body>
</html>

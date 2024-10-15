<!DOCTYPE html>
<html>
<head dir="rtl">
    <title>Print Gas For Apartment {{ $gas->apartment->title }}</title>
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
            <b> {{ $gas->date->toDateString() }} </b>
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
                <td> {{ $gas->id }} </td>
            </tr>
            <tr>
                <th> رقم المبنة </th>
                <td> {{ $gas->apartment->tower->name }} </td>
            </tr>
            <tr>
                <th> طابق </th>
                <td> {{ $gas->apartment->level->name }} </td>
            </tr>
            <tr>
                <th> رقم شقة </th>
                <td> {{ $gas->apartment->number }} </td>
            </tr>
            <tr>
                <th> مالك الشقة </th>
                <td> {{ $gas->customer->name }} </td>
            </tr>
            <tr>
                <th> هل شقة عندها مئجر </th>
                <td> {{ $gas->is_rent ? 'نعم' : 'لا' }} </td>
            </tr>
            @if($gas->is_rent)
                <tr>
                    <th> اسم المئجر </th>
                    <td> {{ $gas->rentCustomer->name }} </td>
                </tr>
                <tr>
                    <th> رقم الهاتف </th>
                    <td> {{ $gas->rentCustomer->phone }} </td>
                </tr>
            @endif

            <tr>
                <th>اخر وحدة قبل هذة قائمة</th>
                <td> {{ number_format($gas->last_unit) }} </td>
            </tr>

            <tr>
                <th>وحدة هذى قائمة</th>
                <td> {{ number_format($gas->current_unit) }} </td>
            </tr>

            <tr>
                <th> الوحدة المستهلكة </th>
                <td> {{ number_format($gas->consumption) }} </td>
            </tr>

            <tr>
                <th> سعر الوحدة </th>
                <td> IQD {{ number_format($gas->unit_price, 0) }} </td>
            </tr>

            <tr>
                <th> كلفة وحدات قبل خصم </th>
                <td> IQD {{ number_format($gas->total_before_discount, 0) }} </td>
            </tr>

            <tr>
                <th> خصم </th>
                <td> IQD {{ number_format($gas->discount, 0) }} </td>
            </tr>

            <tr>
                <th> كلفة وحدات مستهلكة </th>
                <td> IQD {{ number_format($gas->total_price, 0) }} </td>
            </tr>

            <tr>
                <th> حالة دفع</th>
                <td> {{ $gas->is_paid ? 'تم الدفع' : 'لم يتم الدفع' }} </td>
            </tr>

            @if($gas->is_paid)
                <tr>
                    <th> تاريخ الدفع </th>
                    <td> {{ $gas->paid_at->toDateString() }} </td>
                </tr>
            @endif
        </tbody>
    </table>

</div>
<br>
<footer class="footer">
    <div style="text-align: center;">
        {!! QrCode::size(150)->generate(route('profile.show', $gas->apartment->uuid)); !!}
        <p> مسح QR كود ل اضهار كل معلومات شقة </p>
    </div>
</footer>
</body>
</html>

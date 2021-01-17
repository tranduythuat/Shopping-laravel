<h4>Hello <i>{{ $demo['transaction']->name }}</i></h4>
<p>Rất cám ơn quý khách đã tin tưởng và lựa chọn dịch vụ tại cửa hàng chúng tôi.</p>

<p>Chi tiết đơn hàng</p>
<table style="border: 1px solid black; text-align: left;">
    <tr>
        <th style="border: 1px solid #ddd;text-align: left;padding:15px">#</th>
        <th style="border: 1px solid #ddd;text-align: left;padding:15px">Name</th>
        <th style="border: 1px solid #ddd;text-align: left;padding:15px">Price</th>
        <th style="border: 1px solid #ddd;text-align: left;padding:15px">Quanity</th>
        <th style="border: 1px solid #ddd;text-align: left;padding:15px">Amount</th>
    </tr>
    @php
        $count = 1;
    @endphp
    @foreach ($demo['orders'] as $order_item)
        <tr>
            <td style="border: 1px solid #ddd;text-align: left;padding:15px">{{ $count++ }}</td>
            <td style="border: 1px solid #ddd;text-align: left;padding:15px">{{ $order_item->colorSize->productColor->product->name}}</td>
            <td style="border: 1px solid #ddd;text-align: left;padding:15px">${{ number_format($order_item->price, '2', ',', '.') }}</td>
            <td style="border: 1px solid #ddd;text-align: left;padding:15px">{{ $order_item->quanity }}</td>
            <td style="border: 1px solid #ddd;text-align: left;padding:15px">${{ number_format($order_item->price*$order_item->quanity, '2', ',', '.') }}</td>
        </tr>
    @endforeach
</table>
<p>Transport fee: $1.5</p>
<h3>Total price: ${{ number_format($demo['transaction']->amount, '2', ',', '.') }}</h3>

<p><strong>Dia chi nhan hang: </strong>{{ $demo['transaction']->address }}</p>
<p><strong>Dia chi thanh toan: </strong>{{ $demo['transaction']->address }}</p>

<p>Neu co bat ky van de gi, hay goi ngay den tong dai tro giup cua chung toi tai day</p>

<p>Hotline: 0362668855</p>

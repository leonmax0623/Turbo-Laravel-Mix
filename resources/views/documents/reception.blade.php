<style>
    * {
        padding: 0;
        margin: 0;
        -webkit-box-sizing: border-box;
        box-sizing: border-box;
        font-family: "DejaVu Sans", Arial, sans-serif;
        font-size: 12px;
    }

    #main {
        position: relative;
    }

    #wrapper {
        max-width: 1024px;
        margin: 30px;
        padding: 1em;
        -webkit-box-shadow: 0 0 1px 0 black;
        box-shadow: 0 0 1px 0 black;
        background-color: rgba(250, 250, 250, 0.3);
    }

    .sto-w-50 {
        width: 50%;
    }

    .sto-p-1 {
        padding: 0.5em;
    }

    .sto-text-right {
        text-align: right;
    }

    .sto-text-center {
        text-align: center;
    }

    .sto-mb-1 {
        margin-bottom: 0.5em;
    }

    .sto-mb-2 {
        margin-bottom: 1em;
    }

    .sto-mb-3 {
        margin-bottom: 1.5em;
    }

    .sto-mb-4 {
        margin-bottom: 2em;
    }

    .sto-mx-auto {
        margin: auto;
    }

    .bold {
        font-weight: bold;
    }

    .sto-border {
        border: 1px solid black;
    }

    ul {
        list-style: none;
    }
    ul .list-row {
        padding: 0.5em;
        border: 1px solid gray;
    }

    .sto-flex {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
    }

    .sto-space-between {
        -webkit-box-pack: justify;
        -ms-flex-pack: justify;
        justify-content: space-between;
    }

    .sto-table td,
    .sto-table th {
        text-align: center;
        padding: 0.3em;
    }

    .sto-table thead td,
    .sto-table thead th,
    .sto-table tbody td,
    .sto-table tbody th {
        -webkit-box-shadow: 0 0 0 0.5px black;
        box-shadow: 0 0 0 0.5px black;
    }
    .sto-table thead tr,
    .sto-table tbody tr {
        -webkit-box-shadow: 0 0 0 0.5px black;
        box-shadow: 0 0 0 0.5px black;
    }
    .sto-check-client-info {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex; -webkit-box-orient: horizontal; -webkit-box-direction: normal; -ms-flex-direction: row; flex-direction: row; -ms-flex-wrap: wrap; flex-wrap: wrap;
    }
</style>

<div id="main">
    <div id="wrapper" class="bg-gray-50 w-full border rounded border-gray-200 sto-mx-auto mt-3 p-3 wrapper-temp">
        <div class="sto-mb-3 sto-text-right">
        <h4>
            Приложение 6 к заказ-наряду № {!! data_get($data, 'order_id') !!}
            от {!! data_get($data, 'order_created_at') !!}
        </h4>
        </div>

        <div class="sto-mb-3 sto-text-center">
            <h3 class="sto-mb-1">ПРИЕМО-СДАТОЧНЫЙ АКТ</h3>
            <p>выполненных работ</p>
        </div>

        <div class="sto-text-right">
            <span>{!! data_get($data, 'order_created_at') !!}</span>
        </div>

        <div>
            <p>Мы, нижеподписавшиеся:</p>
        </div>

        <ul class="sto-mb-2">
            <li class="list-row bold sto-flex">
                Исполнитель: {!! data_get($data, 'order_user_name') !!}
                {!! data_get($data, 'order_user_surname') !!} {!! data_get($data, 'order_user_middle_name') !!}
            </li>
            <li class="list-row bold sto-flex">Заказчик: {!! data_get($data, 'order_client_surname') !!}
                {!! data_get($data, 'order_client_name') !!}
                {!! data_get($data, 'order_client_middle_name') !!}
            </li>
            <li class="list-row bold sto-flex">
                адрес заказчика: {!! data_get($data, 'order_client_address') !!}
                телефоны: {!! data_get($data, 'order_client_phones') !!}
            </li>
            <li class="list-row bold sto-flex">
                <div>
                    <p>Плательщик: {!! data_get($data, 'order_client_surname') !!}
                        {!! data_get($data, 'order_client_name') !!}
                        {!! data_get($data, 'order_client_middle_name') !!}
                    </p>
                    <p>ИНН адрес:________, телефоны: {!! data_get($data, 'order_client_phones') !!}</p>
                </div>
            </li>
        </ul>

        <p class="sto-mb-1">
            Подписали настоящий Акт о том, что Исполнитель сдал, а Заказчик принял комплекс работ (услуг) по техническому обслуживанию и ремонту автотранспортного средства.
        </p>

        <div class="sto-border sto-flex sto-p-1 sto-mb-1 sto-check-client-info">
            <ul class="sto-w-50">
                <li class="bold sto-mb-1">Автомототранспортное средство ___________:</li>
                <li class="bold sto-mb-1">Марка, модель: {!! data_get($data, 'order_car_model_name') !!}</li>
                <li class="bold sto-mb-1">Год вып: {!! data_get($data, 'order_car_year') !!}</li>
                <li class="bold sto-mb-1">Гос. номер: {!! data_get($data, 'order_car_number') !!}</li>
                <li class="bold sto-mb-1">VIN: {!! data_get($data, 'order_car_vin') !!}</li>
                <li class="bold sto-mb-1">Двигатель: {!! data_get($data, 'order_car_fuel_name') !!}</li>
            </ul>

            <ul>
                <li class="bold sto-mb-1">Шасси №: ___________</li>
                <li class="bold sto-mb-1">Кузов №: {!! data_get($data, 'order_car_body') !!}</li>
                <li class="bold sto-mb-1">Цвет: {!! data_get($data, 'order_car_color') !!}</li>
                <li class="bold sto-mb-1">Тех. паспорт: ___________</li>
                <li class="bold sto-mb-1">Пробег: ________</li>
            </ul>
        </div>

        <div class="sto-mb-3">
            <p>согласно Перечню выполненных работ (Заказ-наряд).	</p>
            <p>
                &nbsp;&nbsp;&nbsp;&nbsp;В ходе сдачи приемки выполненных работ (оказанных услуг) Заказчиком с участием
                Исполнителя проверены комплектность и техническое состояние автомототранспортного
                средства, объем и качество выполненных работ (оказанных услуг), исправность узлов и агрегатов.
            </p>
            <p>&nbsp;&nbsp;&nbsp;&nbsp;Претензий по срокам и качеству выполненных работ (оказанных услуг) не имеется.	</p>
            <p>&nbsp;&nbsp;&nbsp;&nbsp;Замененные (неисправные) и неизрасходованные узлы и детали Заказчиком получены.</p>
        </div>

        <ul class="sto-mb-4">
            <li class="sto-flex sto-space-between sto-mb-2">
                <span style="padding-right: 30px">Заказчик</span>
                <span>___________________________ /{!! data_get($data, 'order_client_surname') !!}
                    {!! data_get($data, 'order_client_name') !!}
                    {!! data_get($data, 'order_client_middle_name') !!}/</span>
            </li>
            <li class="sto-flex sto-space-between sto-mb-2">
                <span style="padding-right: 10px">Плательщик</span>
                <span>___________________________ /{!! data_get($data, 'order_client_surname') !!}
                    {!! data_get($data, 'order_client_name') !!}
                    {!! data_get($data, 'order_client_middle_name') !!}/</span>
            </li>
            <li class="sto-flex sto-space-between sto-mb-2">
                <span style="padding-right: 43px">Мастер</span>
                <span>___________________________ /{!! data_get($data, 'order_user_name') !!}
                    {!! data_get($data, 'order_user_surname') !!}
                    {!! data_get($data, 'order_user_middle_name') !!}/</span>
            </li>
        </ul>

    </div>
</div>

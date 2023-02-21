<?php

/*
|--------------------------------------------------------------------------
| Validation Language Lines
|--------------------------------------------------------------------------
|
| The following language lines contain the default error messages used by
| the validator class. Some of these rules have multiple versions such
| as the size rules. Feel free to tweak each of these messages here.
|
*/

return [
    'accepted'             => 'Вы должны принять :attribute.',
    'accepted_if'          => 'Поле :attribute должно быть принято, когда :other соответствует :value.',
    'active_url'           => 'Поле :attribute содержит недействительный URL.',
    'after'                => 'В поле :attribute должна быть дата больше :date.',
    'after_or_equal'       => 'В поле :attribute должна быть дата больше или равняться :date.',
    'alpha'                => 'Поле :attribute может содержать только буквы.',
    'alpha_dash'           => 'Поле :attribute может содержать только буквы, цифры, дефис и нижнее подчеркивание.',
    'alpha_num'            => 'Поле :attribute может содержать только буквы и цифры.',
    'array'                => 'Поле :attribute должно быть массивом.',
    'attached'             => 'Поле :attribute уже прикреплено.',
    'before'               => 'В поле :attribute должна быть дата раньше :date.',
    'before_or_equal'      => 'В поле :attribute должна быть дата раньше или равняться :date.',
    'between'              => [
        'array'   => 'Количество элементов в поле :attribute должно быть между :min и :max.',
        'file'    => 'Размер файла в поле :attribute должен быть между :min и :max Килобайт(а).',
        'numeric' => 'Значение поля :attribute должно быть между :min и :max.',
        'string'  => 'Количество символов в поле :attribute должно быть между :min и :max.',
    ],
    'boolean'              => 'Поле :attribute должно иметь значение логического типа.',
    'confirmed'            => 'Поле :attribute не совпадает с подтверждением.',
    'current_password'     => 'Поле :attribute содержит некорректный пароль.',
    'date'                 => 'Поле :attribute не является датой.',
    'date_equals'          => 'Поле :attribute должно быть датой равной :date.',
    'date_format'          => 'Поле :attribute не соответствует формату :format.',
    'declined'             => 'Поле :attribute должно быть отклонено.',
    'declined_if'          => 'Поле :attribute должно быть отклонено, когда :other равно :value.',
    'different'            => 'Поля :attribute и :other должны различаться.',
    'digits'               => 'Длина цифрового поля :attribute должна быть :digits.',
    'digits_between'       => 'Длина цифрового поля :attribute должна быть между :min и :max.',
    'dimensions'           => 'Поле :attribute имеет недопустимые размеры изображения.',
    'distinct'             => 'Поле :attribute содержит повторяющееся значение.',
    'email'                => 'Поле :attribute должно быть действительным электронным адресом.',
    'ends_with'            => 'Поле :attribute должно заканчиваться одним из следующих значений: :values',
    'exists'               => 'Выбранное значение для :attribute некорректно.',
    'file'                 => 'Поле :attribute должно быть файлом.',
    'filled'               => 'Поле :attribute обязательно для заполнения.',
    'gt'                   => [
        'array'   => 'Количество элементов в поле :attribute должно быть больше :value.',
        'file'    => 'Размер файла в поле :attribute должен быть больше :value Килобайт(а).',
        'numeric' => 'Значение поля :attribute должно быть больше :value.',
        'string'  => 'Количество символов в поле :attribute должно быть больше :value.',
    ],
    'gte'                  => [
        'array'   => 'Количество элементов в поле :attribute должно быть :value или больше.',
        'file'    => 'Размер файла в поле :attribute должен быть :value Килобайт(а) или больше.',
        'numeric' => 'Значение поля :attribute должно быть :value или больше.',
        'string'  => 'Количество символов в поле :attribute должно быть :value или больше.',
    ],
    'image'                => 'Поле :attribute должно быть изображением.',
    'in'                   => 'Выбранное значение для :attribute ошибочно.',
    'in_array'             => 'Поле :attribute не существует в :other.',
    'integer'              => 'Поле :attribute должно быть целым числом.',
    'ip'                   => 'Поле :attribute должно быть действительным IP-адресом.',
    'ipv4'                 => 'Поле :attribute должно быть действительным IPv4-адресом.',
    'ipv6'                 => 'Поле :attribute должно быть действительным IPv6-адресом.',
    'json'                 => 'Поле :attribute должно быть JSON строкой.',
    'lt'                   => [
        'array'   => 'Количество элементов в поле :attribute должно быть меньше :value.',
        'file'    => 'Размер файла в поле :attribute должен быть меньше :value Килобайт(а).',
        'numeric' => 'Значение поля :attribute должно быть меньше :value.',
        'string'  => 'Количество символов в поле :attribute должно быть меньше :value.',
    ],
    'lte'                  => [
        'array'   => 'Количество элементов в поле :attribute должно быть :value или меньше.',
        'file'    => 'Размер файла в поле :attribute должен быть :value Килобайт(а) или меньше.',
        'numeric' => 'Значение поля :attribute должно быть :value или меньше.',
        'string'  => 'Количество символов в поле :attribute должно быть :value или меньше.',
    ],
    'max'                  => [
        'array'   => 'Количество элементов в поле :attribute не может превышать :max.',
        'file'    => 'Размер файла в поле :attribute не может быть больше :max Килобайт(а).',
        'numeric' => 'Значение поля :attribute не может быть больше :max.',
        'string'  => 'Количество символов в поле :attribute не может превышать :max.',
    ],
    'mimes'                => 'Поле :attribute должно быть файлом одного из следующих типов: :values.',
    'mimetypes'            => 'Поле :attribute должно быть файлом одного из следующих типов: :values.',
    'min'                  => [
        'array'   => 'Количество элементов в поле :attribute должно быть не меньше :min.',
        'file'    => 'Размер файла в поле :attribute должен быть не меньше :min Килобайт(а).',
        'numeric' => 'Значение поля :attribute должно быть не меньше :min.',
        'string'  => 'Количество символов в поле :attribute должно быть не меньше :min.',
    ],
    'multiple_of'          => 'Значение поля :attribute должно быть кратным :value',
    'not_in'               => 'Выбранное значение для :attribute ошибочно.',
    'not_regex'            => 'Выбранный формат для :attribute ошибочный.',
    'numeric'              => 'Поле :attribute должно быть числом.',
    'password'             => 'Неверный пароль.',
    'present'              => 'Поле :attribute должно присутствовать.',
    'prohibited'           => 'Поле :attribute запрещено.',
    'prohibited_if'        => 'Поле :attribute запрещено, когда :other равно :value.',
    'prohibited_unless'    => 'Поле :attribute запрещено, если :other не входит в :values.',
    'prohibits'            => 'Поле :attribute запрещает присутствие :other.',
    'regex'                => 'Поле :attribute имеет ошибочный формат.',
    'relatable'            => 'Поле :attribute не может быть связано с этим ресурсом.',
    'required'             => 'Поле :attribute обязательно для заполнения.',
    'required_if'          => 'Поле :attribute обязательно для заполнения, когда :other равно :value.',
    'required_unless'      => 'Поле :attribute обязательно для заполнения, когда :other не равно :values.',
    'required_with'        => 'Поле :attribute обязательно для заполнения, когда :values указано.',
    'required_with_all'    => 'Поле :attribute обязательно для заполнения, когда :values указано.',
    'required_without'     => 'Поле :attribute обязательно для заполнения, когда :values не указано.',
    'required_without_all' => 'Поле :attribute обязательно для заполнения, когда ни одно из :values не указано.',
    'same'                 => 'Значения полей :attribute и :other должны совпадать.',
    'size'                 => [
        'array'   => 'Количество элементов в поле :attribute должно быть равным :size.',
        'file'    => 'Размер файла в поле :attribute должен быть равен :size Килобайт(а).',
        'numeric' => 'Значение поля :attribute должно быть равным :size.',
        'string'  => 'Количество символов в поле :attribute должно быть равным :size.',
    ],
    'starts_with'          => 'Поле :attribute должно начинаться из одного из следующих значений: :values',
    'string'               => 'Поле :attribute должно быть строкой.',
    'timezone'             => 'Поле :attribute должно быть действительным часовым поясом.',
    'unique'               => 'Такое значение поля :attribute уже существует.',
    'uploaded'             => 'Загрузка поля :attribute не удалась.',
    'url'                  => 'Поле :attribute имеет ошибочный формат URL.',
    'uuid'                 => 'Поле :attribute должно быть корректным UUID.',
    'custom'               => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],
    'attributes' => [
        'name'                      => 'Название',
        'gender'                    => 'Пол',
        'surname'                   => 'Фамилия',
        'middle_name'               => 'Отчество',
        'car_mark_id'               => 'Марка',
        'number'                    => 'Гос номер',
        'vin'                       => 'Вин номер',
        'year'                      => 'Год выпуска',
        'body'                      => 'Кузов',
        'color'                     => 'Цвет',
        'notes'                     => 'Примечание',
        'fuel_id'                   => 'Топливо',
        'engine_volume_id'          => 'Двигатель',
        'client_id'                 => 'Клиент',
        'car_model_id'              => 'Модель',
        'address'                   => 'Адрес',
        'passport'                  => 'Паспорт',
        'born_at'                   => 'День рождения',
        'phones'                    => 'Телефон',
        'emails'                    => 'Почта',
        'department_id'             => 'Филиал',
        'description'               => 'Описание',
        'comments'                  => 'Комментарии',
        'document_template_id'      => 'Шаблон диагностической карты',
        'order_id'                  => 'Заказ-наряд',
        'template'                  => 'Шаблон',
        'value'                     => 'Объём двигателя',
        'operation_type'            => 'Тип операции',
        'payment_type'              => 'Тип оплаты',
        'sum'                       => 'Сумма',
        'finance_group_id'          => 'Группа',
        'data'                      => 'Под пункты',
        'task_id'                   => 'Задача',
        'title'                     => 'Название',
        'appeal_reason_id'          => 'Причина обращения',
        'car_id'                    => 'Автомобиль',
        'user_id'                   => 'Сотрудник',
        'order_stage_id'            => 'Этап',
        'process_category_id'       => 'Категория процесса',
        'comment'                   => 'Комментарий',
        'type'                      => 'Тип',
        'status'                    => 'Статус',
        'role_id'                   => 'Роль',
        'time'                      => 'Время',
        'position'                  => 'Позиция',
        'pipelines'                 => 'Воронки',
        'process_checkboxes'        => 'Чек листы',
        'process_categories'        => 'Категории',
        'is_map'                    => 'Тип',
        'map_id'                    => 'Диагностическая карта',
        'temp_file_ids'             => 'Файлы',
        'delete_file_ids'           => 'Файлы',
        'is_checked'                => 'Выбран',
        'count'                     => 'Количество',
        'product_id'                => 'Продукт',
        'place'                     => 'Место на складе',
        'input_sum'                 => 'Стоимость в закупке',
        'output_sum'                => 'Стоимость для реализации',
        'photo'                     => 'Фото',
        'sku'                       => 'Артикул',
        'storage_id'                => 'Склад',
        'producer_id'               => 'Производитель',
        'avatar'                    => 'Фото',
        'password'                  => 'Пароль',
        'email'                     => 'Почта',
        'about'                     => 'О себе',
        'office_position'           => 'Поведение приложения',
        'is_about_visible'          => 'Скрыть информацию о себе',
        'is_born_at_visible'        => 'Скрыть день рождения',
        'permissions'               => 'Роли доступа',
        'pipeline_id'               => 'Воронка',
        'tasks'                     => 'Задачи',
        'end_at'                    => 'Конец в',
        'start_at'                  => 'Начать с',
        'deadline_at'               => 'Крайний срок',
        'stage_id'                  => 'Этап',
        'checkboxes'                => 'Чек листы',
        'ids'                       => 'Идентификаторы',
        'files'                     => 'Файлы',
        'is_active'                 => 'Активный статус',
    ],
];

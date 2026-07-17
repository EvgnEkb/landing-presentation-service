<!DOCTYPE html>
<html>

<head>
    <title>Новое сообщение</title>
</head>

<body style="font-family: Arial, sans-serif; line-height: 1.6;">
    <h2>Новое сообщение с сайта</h2>
    <p><strong>Имя:</strong> {{ $data['name'] }}</p>
    <p><strong>Телефон:</strong> {{ $data['phone'] }}</p>
    <p><strong>Email:</strong> {{ $data['email'] }}</p>
    <p><strong>Комментарий:</strong></p>
    <p style="background: #f4f4f4; padding: 10px; border-radius: 5px;">
        {{ $data['comment'] }}
    </p>
</body>

</html>

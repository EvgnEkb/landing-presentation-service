<!DOCTYPE html>
<html>

<head>
    <title>Копия вашего обращения</title>
</head>

<body style="font-family: Arial, sans-serif; line-height: 1.6;">
    <h2>Копия вашего обращения</h2>
    <p>Здравствуйте, <strong>{{ $data['name'] }}</strong>!</p>
    <p>Мы получили ваше сообщение и ответим вам в ближайшее время.</p>
    <p><strong>Ваш телефон:</strong> {{ $data['phone'] }}</p>
    <p><strong>Ваш email:</strong> {{ $data['email'] }}</p>
    <p><strong>Ваш комментарий:</strong></p>
    <p style="background: #f4f4f4; padding: 10px; border-radius: 5px;">
        {{ $data['comment'] }}
    </p>
    <hr>
    <p style="color: #888; font-size: 0.9em;">Это автоматическое уведомление. Пожалуйста, не отвечайте на это письмо.</p>
</body>

</html>

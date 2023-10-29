function onClick(e) {
    e.preventDefault();
    grecaptcha.ready(function() {
    grecaptcha.execute('6LeYYnQdAAAAADzsZvpgdKTOsYaARwzbILkUUoIy', {action: 'submit'}).then(function(token) {
        console.log("Connected");
        });
    });
}
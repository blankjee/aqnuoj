$(document).ready(function () {
    alert("ddd");
    $("#register-form").validate({
        rules: {
            user_id: {
                required: true,
                minlength: 3,
                maxlength: 20
            },
            password: {
                required: true,
                minlength: 6,
                maxlength: 20
            },
            nick: {
                required: true,
                maxlength: 32
            },
            email: {
                required: true,
                email: true
            },
            school: {
                required: true,
                maxlength: 32
            }
        },
        messages: {
            user_id: {
                required: "请输入用户名",
                minlength: "请输入3位以上用户名",
                maxlength: "请输入20位以下用户名"
            },
            password: {
                required: "请输入密码",
                minlength: "密码长度不能小于6位",
                maxlength: "请输入20位以下密码"
            },
            nick: {
                required: "请输入昵称",
                maxlength: "请输入32位以下昵称"
            },
            email: {
                required: "请输入邮箱",
            },
            school: {
                required: "请输入学校名称",
                maxlength: "请输入32位以下的字符"
            }
        },

    });
});
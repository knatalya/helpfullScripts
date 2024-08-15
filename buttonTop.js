const scrollToTopBtn = document.createElement("button");
scrollToTopBtn.id = "scrollToTopBtn";
scrollToTopBtn.title = "Вернуться вверх";
scrollToTopBtn.innerHTML = "↑";
document.body.appendChild(scrollToTopBtn);

const styles = `
    #scrollToTopBtn {
        display: none; /* Изначально скрываем кнопку */
        position: fixed; /* Фиксируем кнопку */
        bottom: 20px; /* Расположение от нижнего края */
        right: 20px; /* Расположение от правого края */
        z-index: 99; /* Кнопка всегда сверху */
        border: none;
        outline: none;
        background-color: #008CBA; /* Цвет фона */
        color: white; /* Цвет текста */
        cursor: pointer; /* Указатель курсора */
        padding: 15px; /* Внутренние отступы */
        border-radius: 10px; /* Скругленные углы */
        font-size: 18px; /* Размер текста */
        box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.3); /* Тень */
        transition: opacity 0.3s; /* Плавное появление и исчезновение */
    }
    #scrollToTopBtn:hover {
        background-color: #555; /* Изменение цвета фона */
    }
`;

const styleSheet = document.createElement("style");
styleSheet.type = "text/css";
styleSheet.innerText = styles;
document.head.appendChild(styleSheet);

window.onscroll = function() {
    if (document.body.scrollTop > 200 || document.documentElement.scrollTop > 200) {
        scrollToTopBtn.style.display = "block";
    } else {
        scrollToTopBtn.style.display = "none";
    }
};

scrollToTopBtn.addEventListener("click", function() {
    document.body.scrollTop = 0; 
    document.documentElement.scrollTop = 0;
});

document.addEventListener('DOMContentLoaded', function () {

   /* Записываем в переменные массив элементов-кнопок и подложку.*/
   let modalButtons = document.querySelectorAll('.btn'),
      closeButtons = document.querySelectorAll('.js-modal-close');

   /* Перебираем массив кнопок */
   modalButtons.forEach(function (item) {

      /* Назначаем каждой кнопке обработчик клика */
      item.addEventListener('click', function (e) {

         /* Предотвращаем стандартное действие элемента.*/
         e.preventDefault();

         /* При каждом клике на кнопку мы будем забирать содержимое атрибута data-modal
            и будем искать модальное окно с таким же атрибутом. */
         let modalId = this.getAttribute('data-modal'),
            modalElem = document.querySelector('.modal[data-modal="' + modalId + '"]');
         modalElem.classList.add('active');
      }); // end click

   }); // end foreach


   closeButtons.forEach(function (item) {
      item.addEventListener('click', function (e) {
         let parentModal = this.closest('.modal');
         parentModal.classList.remove('active');
      });

   }); // end foreach

   /* При нажаетии на esc окно закрывается*/
   document.body.addEventListener('keyup', function (e) {
      let key = e.keyCode;
      if (key == 27) {
         document.querySelector('.modal.active').classList.remove('active');
      };
   }, false);



}); // end ready



 document.addEventListener('DOMContentLoaded', function() {
    var tabs = document.querySelectorAll('.nav-tabs li a');

    tabs.forEach(function(tab) {
      tab.addEventListener('click', function(event) {
        event.preventDefault();

        var target = this.getAttribute('data-target');
        var tabPanes = document.querySelectorAll('.tab');

        tabs.forEach(function(tab) {
          tab.parentNode.classList.remove('active');
        });

        tabPanes.forEach(function(pane) {
          pane.classList.remove('active');
        });

        this.parentNode.classList.add('active');
        document.getElementById(target).classList.add('active');

        // Добавляем якорь к URL без перехода к нему
        var urlWithoutHash = window.location.href.split('#')[0];
        window.history.replaceState({}, document.title, urlWithoutHash + '#' + target);
      });
    });

    // Проверяем наличие якоря в URL и открываем соответствующую вкладку
    var currentHash = window.location.hash;
    if (currentHash) {
      var target = currentHash.slice(1);
      var targetTab = document.querySelector('[data-target="' + target + '"]');
      if (targetTab) {
        targetTab.click();
      }
    }
  });
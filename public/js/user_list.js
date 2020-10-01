import $ from 'jquery';
import Dropzone from 'dropzone';
import 'dropzone/dist/dropzone.css';
import '../css/user_list.css';

Dropzone.autoDiscover = false;

const $scrolltop = $('#scrolltop');
const setClientIconUrl = '' + $('html').data('set-client-icon');
const $setClientIconModal = window.$('#client-icon-modal');
/** @type {JQuery} */
let $currentClientItem;

const clientIconDropzone = new Dropzone(
  '#client-icon-dropzone',
  {
    url: setClientIconUrl,
    acceptedFiles: '.jpg,.jpeg,.png',
    maxFilesize: 1,
    maxFiles: 1,
    uploadMultiple: false,
    autoProcessQueue: false
  }
);

clientIconDropzone.on('maxfilesexceeded', file => {
  clientIconDropzone.removeAllFiles();
  clientIconDropzone.addFile(file);
}).on('success', (file, response) => {
  $currentClientItem.find('img.client-logo').prop('src', response ? `${response.url}?${Date.now()}` : '');
  $setClientIconModal.modal('close');
  clientIconDropzone.removeAllFiles();
});

$('#clients').on('click', '.client-logo', ({ target }) => {
  $currentClientItem = $(target).closest('li');
  const clientId = +$currentClientItem.data('id');
  clientIconDropzone.options.url = get_setClientIconUrl(clientId);
  $setClientIconModal.modal('open');
});

$('#client-icon-upload-btn').on('click', function(){
  clientIconDropzone.processQueue();
});



/**
 * @param {number} clientId client id
 */
function get_setClientIconUrl(clientId) {
  return setClientIconUrl.replace(0, clientId);
}

/**
 * @param {string} name user name
 */
function filterByUserName(name) {
  const $items = $('.users-list--item');

  if (!name) {
    $items.show();
  } else {
    $items
      .hide()
      .filter(
        (i, e) => $(e).find('.user-name').html().toLowerCase().includes(name)
      ).show();
  }

  return true;
}

$('#user-search').on('input', e => {
  /** @type {HTMLInputElement} */
  const input = e.target;
  const sanitized = input.value.replace(/[^A-ÿ]+/g, ' ').trim();
  filterByUserName(sanitized);
});

$(document).on('scroll', e => {
  const doShow = window.pageYOffset > window.innerHeight;
  $scrolltop.css('opacity', +doShow);
});

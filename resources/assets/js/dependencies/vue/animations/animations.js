Vue.transition('fade', {
    enterClass: 'fadeIn',
    leaveClass: 'fadeOut'
});

Vue.transition('slide', {
    enterClass: 'slideInLeft',
    leaveClass: 'slideOutLeft'
});

Vue.transition('fade-slide', {
    enterClass: 'fadeInDown',
    leaveClass: 'fadeOutUp'
});

Vue.transition('slide-down', {
    enterClass: 'slideInDown',
    leaveClass: 'slideOutUp'
});
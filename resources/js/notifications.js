const notification = (title, type) => {
    return {
        'title' : title,
        'iconColor': 'white',
        'icon': type,
        'customClass': {
            popup: 'colored-toast',
        },
        'position' : 'top-right',
        'toast' : true,
        'timer' : 3000,
        'timerProgressBar' : true,
        'showConfirmButton' : false,
    }
}

export {
    notification,
}
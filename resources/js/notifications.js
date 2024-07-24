const notification = (text, type, title=null) => {
    return {
        'title': title,
        'text' : text,
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
export const btn = (list) => {
   
    const classes = {
        'px-4 py-2 text-md font-bold text-white': true,
        'border border-gray-200': true,
        'hover:text-blue-700': true, 
        'focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700': true
    };
    
    if (typeof list === 'string') {
        classes[list] = true;
    } else if (typeof list === 'object') {
        return Object.assign(JSON.parse(JSON.stringify(classes)), list);
    }
    
    return classes;
};


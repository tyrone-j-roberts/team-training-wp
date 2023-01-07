export function generateUniqueKey(prefix)  {
    const rand = (Math.floor(Math.random() * 10000) + 10000).toString().substring(1);
    const timestamp = new Date().getTime(); 
    
    prefix = typeof prefix == 'string' ? prefix.trim() : '';

    return `${prefix}${timestamp}${rand}`;
};
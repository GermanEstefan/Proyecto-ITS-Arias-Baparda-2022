export const capitalizeString = (value) => {
    if(!value) return ''
    const stringSplitted = value.split(' ');
    const arrayOfWordsCapitalized = stringSplitted.map( value => {
        const firstLetterCapitalized = value[0].toUpperCase();
        const wordWithoutFirstLetter = value.substring(1).toLowerCase();
        return firstLetterCapitalized+wordWithoutFirstLetter;
    })
    return arrayOfWordsCapitalized.toString().replace(',',' ');
}
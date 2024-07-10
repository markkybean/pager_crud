function is_date_valid(date_str) 
{
    var match = date_str.match(/(\d{1,2})-(\d{1,2})-(\d{4})/);
    if (!match) return false;
    var year = parseInt(match[3]), month = parseInt(match[1],10) - 1, day = parseInt(match[2]);
    var d = new Date(year, month, day);
    var year_diff = year - d.getYear(); 
    return (year_diff == 0 || year_diff == 1900) &&
           (d.getMonth() == month) &&
           (d.getDate() == day);
}
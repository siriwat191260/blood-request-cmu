
import { useCurrentTime } from "./useCurrentTime.js";
// dateUtils.js

// Function to parse date
export const parseDate = (date_time) => {
  const date_time_TypeDateTime = new Date(date_time);
  const date = date_time_TypeDateTime.toLocaleDateString("th-TH", {
    year: "numeric",
    month: "short",
    day: "numeric",
  });
  return date;
}

// Function to parse time
export const parseTime = (date_time) => {
  const dateTime = new Date(date_time);
  const hours = dateTime.getHours().toString().padStart(2, "0");
  const minutes = dateTime.getMinutes().toString().padStart(2, "0");
  const time = `${hours}:${minutes}`;
  return time;
}

export const currentDate = () => {
  const current = new Date();
  const date = current.toLocaleDateString("th-TH", {
    year: "numeric",
    month: "short",
    day: "numeric",
  });
  return date;
}

export const currentTime = () => {
  const { currentTime } = useCurrentTime();
  const time =
    currentTime.value.getHours() +
    ":" +
    currentTime.value.getMinutes() +
    ":" +
    currentTime.value.getSeconds();
  return time;
}



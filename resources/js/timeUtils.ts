/**
 * General functions for time.
 *
 * Usage: import { formatTime } from './timeUtils';
 */

// Format a date to a time string.
export const formatTime = (date: Date) => {
    const hours = date.getHours();
    const minutes = date.getMinutes();
    return `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}`;
};

// Format a date to a date string.
export const formattedDate = (dateString: string) => {
    const date = new Date(dateString);
    return date.toISOString().split('T')[0];
};

// Checks if two date strings are the same day.
export const isSameDay = (dateStringFrom: string, dateStringTo: string) => {
    const fromDate = new Date(dateStringFrom);
    const toDate = new Date(dateStringTo);
    return fromDate.toLocaleDateString() === toDate.toLocaleDateString();
};

// Format an event time to a readable string.
export const formattedEventTime = (dateStringFrom: string, dateStringTo: string) => {
    // No endtime, so show only the start time.
    if (dateStringTo === null) {
        const fromDate = new Date(dateStringFrom);
        return `${fromDate.toISOString().split('T')[0]} ${formatTime(fromDate)}`;
    }

    // Same day but different start and end time, so show only the start and end time.
    if (isSameDay(dateStringFrom, dateStringTo)) {
        const fromDate = new Date(dateStringFrom);
        const toDate = new Date(dateStringTo);
        return `${fromDate.toISOString().split('T')[0]} ${formatTime(fromDate)} - ${formatTime(toDate)}`;
    }

    // Different days, so show both the start and end date.
    return `${formattedDate(dateStringFrom)} ${formatTime(new Date(dateStringFrom))} - ${formattedDate(dateStringTo)} ${formatTime(new Date(dateStringTo))}`;
};

import React from "react";

import Day from "./Day";

export default function Week({weekIndex, weekKey, onRemoveWeekButtonPress, onEditWorkoutButtonClick}) {

    const renderDays = () => {
        const days = [];
        for (let i = 0; i < 7; i++) {
            days.push(
                <Day key={`week-${weekIndex}-day-${i}`}
                    weekIndex={weekIndex}
                    dayIndex={i}
                    onEditWorkoutButtonClick={onEditWorkoutButtonClick} />
            )
        }
        return days;
    };

    return (
        <>
            <h2>Week {weekIndex + 1}</h2>
            <div className="programme-schedule__week">
                <div className="programme-schedule__days">
                    { renderDays() }
                </div>
                <div className="actions">
                    {
                        weekIndex > 0 && (
                            <button className="delete-btn"
                                    type="button"
                                    onClick={() => onRemoveWeekButtonPress(weekKey)}>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 512c141.4 0 256-114.6 256-256S397.4 0 256 0S0 114.6 0 256S114.6 512 256 512zM175 175c9.4-9.4 24.6-9.4 33.9 0l47 47 47-47c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9l-47 47 47 47c9.4 9.4 9.4 24.6 0 33.9s-24.6 9.4-33.9 0l-47-47-47 47c-9.4 9.4-24.6 9.4-33.9 0s-9.4-24.6 0-33.9l47-47-47-47c-9.4-9.4-9.4-24.6 0-33.9z" fill="currentColor" /></svg>
                            </button>
                        )
                    }
                </div>
            </div>
        </>
    )
};
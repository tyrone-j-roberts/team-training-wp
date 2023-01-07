import React, { useState } from "react";

export default function Day({ weekIndex, dayIndex, onEditWorkoutButtonClick }) {

    const [restDay, setRestDay] = useState(false);

    return (
        <div className="programme-schedule__day">
            <span className="day-label">Day { (dayIndex + 1) + (7 * weekIndex) }</span>
            <ul className="exercises"></ul>
            <div className="actions">
                <button className="button" 
                        type="button"
                        disabled={restDay}
                        onClick={() => onEditWorkoutButtonClick(weekIndex, dayIndex)}>Add Workout</button>
                <label className="selectit">
                    <input value="1" type="checkbox" onChange={() => setRestDay(!restDay)} checked={restDay} /> Rest Day
                </label>
            </div>
            
        </div>
    )
}
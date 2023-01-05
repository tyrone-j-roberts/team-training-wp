import React, { useState } from "react";

const days = ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"];

export default function Day({ weekIndex, dayIndex }) {

    const [restDay, setRestDay] = useState(false);

    const onAddExerciseButtonClick = () => {
        console.log(weekIndex, dayIndex);
    };

    return (
        <div className="programme-schedule__day">
            <span className="day-label">{days[dayIndex]}</span>
            <ul className="exercises"></ul>
            <div className="actions">
                <button className="button" 
                        type="button"
                        disabled={restDay}
                        onClick={onAddExerciseButtonClick}>Add Exercise</button>
                <label className="selectit">
                    <input value="1" type="checkbox" onChange={() => setRestDay(!restDay)} checked={restDay} /> Rest Day
                </label>
            </div>
            
        </div>
    )
}
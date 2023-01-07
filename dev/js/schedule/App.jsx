import React, { useState, useEffect } from "react";

import Week from "./components/Week";
import WorkoutEditor from "./components/WorkoutEditor";

import { generateUniqueKey } from "../helpers";

export default function App() {

    const [weeks, setWeeks] = useState([ generateUniqueKey('wk') ]);
    const [workoutEdit, setWorkoutEdit] = useState(null);

    const renderWeeks = () => {
        return weeks.map((key, index) => (
            <Week key={key} 
                    weekKey={key}
                    weekIndex={index}
                    onRemoveWeekButtonPress={onRemoveWeekButtonPress}
                    onEditWorkoutButtonClick={onEditWorkoutButtonClick} />
        ));
    }

    const onEditWorkoutButtonClick = (weekIndex, dayIndex) => {
        setWorkoutEdit({
            id: 'new',
            weekIndex: weekIndex,
            dayIndex: dayIndex
        })
    }

    const onAddWeekButtonClick = () => {
        setWeeks(current => [
            ...current,
            generateUniqueKey('wk')
        ]);
    };

    const onRemoveWeekButtonPress = (key) => {
        const newWeeks = [...weeks];
        const removeIndex = newWeeks.indexOf(key);
        if (removeIndex > -1) {
            newWeeks.splice(removeIndex, 1);
            setWeeks(newWeeks);
        }
    }

    useEffect(() => {
        console.log(weeks);
    }, [weeks]);

    return (
        <div className="programme-schedule">
            <div className="programme-schedule__weeks">
                {renderWeeks()}
            </div>
            <div className="programme-schedule__actions">
                <button type="button"
                        className="button button-primary"
                        onClick={onAddWeekButtonClick}>Add Week</button>
            </div>
            {
                workoutEdit && (
                    <WorkoutEditor data={workoutEdit}
                                    onClose={() => setWorkoutEdit(null)} />
                )
            }
        </div>
    );
}
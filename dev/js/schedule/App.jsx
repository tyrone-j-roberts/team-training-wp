import React, { useState, useEffect } from "react";

import Week from "./components/Week";

const generateUniqueKey = () => {
    const rand = (Math.floor(Math.random() * 10000) + 10000).toString().substring(1);
    const timestamp = new Date().getTime(); 
    return `${rand}-${timestamp}`;
};

export default function App() {

    const [weeks, setWeeks] = useState([ generateUniqueKey() ]);

    const renderWeeks = () => {
        return weeks.map((key, index) => (
            <Week key={key} 
                    weekKey={key}
                    weekIndex={index}
                    onRemoveWeekButtonPress={onRemoveWeekButtonPress} />
        ));
    }

    const onAddWeekButtonClick = () => {
        setWeeks(current => [
            ...current,
            generateUniqueKey()
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
        </div>
    );
}
import React, { useState, useEffect } from "react";

import Exercise from "./Exercise";
import { generateUniqueKey } from "../../helpers";

export default function WorkoutEditor({ data, onClose }) {

    const [workout, setWorkout] = useState({
        title: "",
        firstExceriseIsWarmUp: false
    });

    const [exercises, setExercises] = useState([]);
    const [exerciseEditIndex, setExerciseEditIndex] = useState(null);

    const updateExerciseField = (index, field, value) => {
        const newExercises = [...exercises];
        newExercises[index][field] = value;
        setExercises(newExercises);
    }

    const onAddExerciseClick = () => {
        const newItem = {
            key: generateUniqueKey(`ex`),
            title: "",
            subtitle: "",
            thumbnail: null,
            videos: [],
            objectives: []
        };
        setExercises(items => [ ...items, newItem ]);
        setExerciseEditIndex(exercises.length);
    };

    return (
        <div className="workout-editor">
            <div className="workout-editor__curtain"></div>
            <div className="workout-editor__inner">

                <div className="workout-editor__header">
                    <h2>{ data.id == 'new' ? 'New workout' : 'Edit workout' }</h2>
                    <button type="button" 
                            className="close-btn"
                            onClick={onClose}>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path d="M315.3 411.3c-6.253 6.253-16.37 6.253-22.63 0L160 278.6l-132.7 132.7c-6.253 6.253-16.37 6.253-22.63 0c-6.253-6.253-6.253-16.37 0-22.63L137.4 256L4.69 123.3c-6.253-6.253-6.253-16.37 0-22.63c6.253-6.253 16.37-6.253 22.63 0L160 233.4l132.7-132.7c6.253-6.253 16.37-6.253 22.63 0c6.253 6.253 6.253 16.37 0 22.63L182.6 256l132.7 132.7C321.6 394.9 321.6 405.1 315.3 411.3z" fill="currentColor" /></svg>
                    </button>
                </div>

                <div className="workout-editor__content">

                    <div className="field">
                        <label htmlFor="workout-title">Title</label>
                        <input type="text" 
                                name="workout-title" 
                                id="workout-title" 
                                value={workout.title}
                                onChange={(e) => setWorkout(workout => ({ ...workout, title: e.target.value }))} />
                    </div>
                
                    <div className="field">
                        <label className="selectit">
                            <input type="checkbox"
                                    onChange={(e) => setWorkout(workout => ({ ...workout, firstExceriseIsWarmUp: e.target.checked }))}
                                    checked={workout.firstExceriseIsWarmUp} />
                            <span>First exercise is warmup?</span>
                        </label>
                    </div>

                    <div className="workout-editor__exercise">
                        <h4>Exercises</h4>
                        <div className="exercises">
                            {
                                exercises.length < 1 ? (
                                    <p>No exercises added</p>
                                ) : exercises.map((exercise, index) => (
                                    <Exercise key={`exercise-${index}`}
                                            index={index}
                                            exercise={exercise}
                                            updateExerciseField={updateExerciseField}
                                            open={exerciseEditIndex == index}
                                            setEditIndex={setExerciseEditIndex} />
                                ))
                            }
                        </div>
                        <button 
                            type="button"
                            className="button"
                            onClick={onAddExerciseClick}>Add Exercise</button>
                    </div>

                </div>

                <div className="workout-editor__actions">
                    <button type="button"
                            className="button"
                            onClick={onClose}>Close</button>
                    <button type="button"
                            className="button-primary">Save</button>
                </div>

            </div>
        </div>
    )

}
import React, { useState, useCallback } from "react";

export default function Exercise({ index, open, exercise, updateExerciseField, setEditIndex }) {

    const onSelectImageClick = useCallback(() => {

        window.fileFrame = wp.media({
            frame:   'select',
            state:   'mystate',
            library:   { type: 'image' },
            multiple:   false
        });

        fileFrame.states.add([
            new wp.media.controller.Library({
              id: 'mystate',
              title: 'Select Image',
              priority: 20,
              toolbar: 'select',
              filterable: 'uploaded',
              library: wp.media.query( fileFrame.options.library ),
              multiple: fileFrame.options.multiple ? 'reset' : false,
              editable: true,
              displayUserSettings: false,
              displaySettings: true,
              allowLocalEdits: true
            })
          ]);

          fileFrame.on('select', function() {
            var mediaAttachment = fileFrame.state().get('selection').first().toJSON();
            console.log(mediaAttachment);
            updateExerciseField(index, 'thumbnail', mediaAttachment.id);
            fileFrame = null;
          });
          
          fileFrame.open();

    }, [index]);

    return (
        <div className="exercise">
            
            <div className="exercise__overview"   
                 onClick={() => setEditIndex(open ? null : index)}>
                <h4>{index + 1}: {exercise.title.length > 0 ? exercise.title : 'Untitled'}</h4>
                <div className="actions">
                    {
                        open ? (
                            <button className="exercise-close-btn"
                                    type="button">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 512c141.4 0 256-114.6 256-256S397.4 0 256 0S0 114.6 0 256S114.6 512 256 512zm97.9-320l-17 17-47 47 47 47 17 17L320 353.9l-17-17-47-47-47 47-17 17L158.1 320l17-17 47-47-47-47-17-17L192 158.1l17 17 47 47 47-47 17-17L353.9 192z" fill="currentColor" /></svg>
                            </button>
                        ) : (
                            <>
                                <button className="remove-btn"
                                        type="button"
                                        onClick={(e) => { alert(`Remove ${index}`); e.stopPropagation(); }}>
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 512c141.4 0 256-114.6 256-256S397.4 0 256 0S0 114.6 0 256S114.6 512 256 512zM160 128h41.4l11.3-11.3 4.7-4.7H224h64 6.6l4.7 4.7L310.6 128H352h16v32H352 160 144V128h16zm0 64H352L336 384H176L160 192z" fill="currentColor" /></svg>
                                </button>
                            </>
                        )
                    }
                </div>
            </div>

            {
                open && (
                    <div className="exercise__editor">
                        <div className="field">
                            <label htmlFor="exercise-title">Title</label>
                            <input type="text" 
                                    id="workout-title" 
                                    value={exercise.title}
                                    onChange={(e) => updateExerciseField(index, 'title', e.target.value)} />
                        </div>
                        <div className="field">
                            <label htmlFor="exercise-subtitle">Subtitle</label>
                            <input type="text" 
                                    id="exercise-subtitle" 
                                    value={exercise.subtitle}
                                    onChange={(e) => updateExerciseField(index, 'subtitle', e.target.value)} />
                        </div>
                        <div className="field">
                            <label>Thumbnail Image</label>
                            { 
                                !exercise.thumbnail ? (
                                    <p>No image selected. <button type="button"
                                                                className="button"
                                                                onClick={() => onSelectImageClick('thumbnail')}>Select Image</button></p>
                                ) : (
                                    <div className="attchment-field-populated">
                                        <img src={`http://teamtraining.test/?attachment_id=${exercise.thumbnail}`} />
                                    </div>
                                )
                            }
                        </div>
                    </div>
                )
            }

        </div>
    ); 
}
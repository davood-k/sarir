import React from 'react';
import ReactDOM from 'react-dom';
import { useState } from 'react';



function Example() {

    const [courseList, setCourseList] = useState([]);
    const [newCourse, setNewCourse] = useState("");

    const handelChange = (event) => {
        setNewCourse(event.target.value);
    }

    const addCourse = () => {
        const course = {
            id: courseList.length === 0 ? 1 : courseList[courseList.length - 1].id + 1,
            courseName: newCourse
        }
        setCourseList([...courseList, course])

    }

    const deleteCourse = (courseId) => {
        setCourseList(courseList.filter((course) => courseId !== course.id))
    }

    return (

        <div className="container">
            <div className="row justify-content-center">
                <div className="col-12">
                    <div className="card-header">اطلاع رسانی</div>
                    <input type="text" onChange={handelChange}></input>
                    <button className="mr-2" onClick={addCourse}>submit</button>
                    <div className="list mt-4">
                        {courseList.map((course, index) => {
                            return (
                                <div key={'div_${index}'}>
                                    <h6 key={'h6_${index}'}>{course.courseName}</h6>
                                    <button key={'btn_${index}'} onClick={() => deleteCourse(course.id)}>x</button>
                                </div>
                            )
                        })
                        }
                    </div>
                </div>
            </div>
        </div>
    );
}

export default Example;

if (document.getElementById('example')) {
    ReactDOM.render(<Example />, document.getElementById('example'));
}

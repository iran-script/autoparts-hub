from src.db_services import get_inspection, get_images, update_images_status, create_anommaly
from fastapi import HTTPException

from src.model import full_process

def compute_inspection(inspection_id):
    inspection = get_inspection(inspection_id)
    if inspection is None:
        raise HTTPException(status_code=404, detail="selected inspection not exists.")
    images = get_images(inspection_id)
    if len(images) < 1:
        raise HTTPException(status_code=404, detail="there are no image yet")

    for i in images:
        img_id, inspect_id, rtn_list = full_process(i["src"], i["lat"], i["lng"], i["image_id"], i["inspection_id"], cmpx=6.25)
        for n in rtn_list:
            success = create_anommaly(inspect_id, img_id, n["lat"], n["lng"], n["temprather"], n["x"], n["y"], n["w"], n["h"])
            if (success):
                update_images_status(img_id)
    
    return f"inspectionId: {inspection_id} computing completed"
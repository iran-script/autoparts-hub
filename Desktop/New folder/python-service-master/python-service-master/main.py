from typing import Union

from fastapi import FastAPI
from pydantic import BaseModel
from fastapi.middleware.cors import CORSMiddleware

from fastapi_utils.tasks import repeat_every

import src.controllers

class Item(BaseModel):
    inspectionId: int



app = FastAPI()

app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

# cron job
@app.on_event("startup")
@repeat_every(seconds=5 * 60 * 60, wait_first=True)     # every 5 hours
def periodic():
    print("hi")

@app.get("/")
async def geta():
    return "test"

@app.post("/startcompute")         
async def create_item(item: Item):
    return src.controllers.compute_inspection(item.inspectionId)
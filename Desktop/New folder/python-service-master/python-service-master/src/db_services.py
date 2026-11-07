import psycopg2
import psycopg2.extras
DATABASE_URL = "postgresql://postgres:root@212.33.199.33:49158/solar"

# SAMPLE_Q = "SELECT id, status, inspection_id, image_id, type_id, lat, lng, temprather, created_at, updated_at, geom, x, y, w, h FROM public.so_anomally;"

conn =  None
def create_anommaly(inspection_id, image_id, lat, lng, temprather, x, y, w, h):
    try:
        with psycopg2.connect(DATABASE_URL) as conn:
            with conn.cursor(cursor_factory=psycopg2.extras.DictCursor) as cur:

                insert_script = """INSERT INTO public.so_anomally (status, inspection_id, image_id, type_id, lat, lng, temprather, created_at, updated_at, geom, x, y, w, h) 
                    VALUES ( 1, %s,  %s,  1,  %s,  %s, %s, now()::timestamp, now()::timestamp, %s, %s, %s, %s, %s)"""
                insert_value = ( inspection_id, image_id, lat, lng, temprather, 'test', x, y, w, h)    # for multiple [(), ()] \ for record in insert_values
                cur.execute(insert_script, insert_value)
            return True

    except Exception as err:
        print(err)
        return False

    finally:
        if conn is not None:
            conn.close()

# fields: id, created_at, updated_at, inspection_id, status, src, creator_id
def get_images(inspection_id: int):
    try:
        with psycopg2.connect(DATABASE_URL) as conn:
            with conn.cursor(cursor_factory=psycopg2.extras.DictCursor) as cur:

                cur.execute(f"SELECT id, created_at, updated_at, inspection_id, status, src, creator_id FROM public.so_images WHERE inspection_id={inspection_id} and status='1';")
                all_data = cur.fetchall()
                return all_data
    
    except Exception as err:
        print(err)
        return None

    finally:
        if conn is not None:
            conn.close()


def update_images_status(id: int):
    try:
        with psycopg2.connect(DATABASE_URL) as conn:
            with conn.cursor(cursor_factory=psycopg2.extras.DictCursor) as cur:
                update_script = f"UPDATE so_images SET status=2 WHERE id={id};"
                cur.execute(update_script)
                return True
        
    except Exception as err:
        print(err)
        return None

    finally:
        if conn is not None:
            conn.close()


def get_inspection(inspectionId: int):
    try:
        with psycopg2.connect(DATABASE_URL) as conn:
            with conn.cursor(cursor_factory=psycopg2.extras.DictCursor) as cur:
                update_script = f"SELECT * FROM so_inspection WHERE id={inspectionId};"
                cur.execute(update_script)
                data = cur.fetchone()
                return data
        
    except Exception as err:
        print(err)
        return None

    finally:
        if conn is not None:
            conn.close()


if __name__ == "__main__":
    res = create_anommaly(1, 1, 40.714, -74.006, 25, 0.14, 0.168, 40, 50)
    if res == True:
        print("inserted")
    # print(get_images(1))